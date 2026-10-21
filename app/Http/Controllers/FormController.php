<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FormController extends Controller
{
    private const QUESTION_TYPES = ['text', 'textarea', 'select', 'radio', 'checkbox'];

    public function index(): View
    {
        $forms = auth()->user()->forms()
            ->withCount([
                'submissions as verified_submissions_count' => fn ($query) => $query->where('status', 'verified'),
            ])
            ->latest()
            ->get();

        return view('dashboard', compact('forms'));
    }

    public function create(): View
    {
        return view('forms.builder', [
            'form' => null,
            'questions' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedFormData($request);

        DB::transaction(function () use ($data): void {
            $form = auth()->user()->forms()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'slug' => (string) Str::uuid(),
            ]);

            $this->syncQuestions($form, $data['questions']);
        });

        return redirect()->route('dashboard')->with('status', 'Form created successfully.');
    }

    public function show(Form $form): View
    {
        $form = $this->ownedForm($form);
        $form->loadCount([
            'submissions as verified_submissions_count' => fn ($query) => $query->where('status', 'verified'),
        ]);

        return view('forms.show', compact('form'));
    }

    public function edit(Form $form): View
    {
        $form = $this->ownedForm($form);
        $questions = $form->activeQuestions()->get();

        return view('forms.builder', compact('form', 'questions'));
    }

    public function update(Request $request, Form $form): RedirectResponse
    {
        $form = $this->ownedForm($form);
        abort_if($form->archived_at !== null, 409, 'Archived forms cannot be edited.');

        $data = $this->validatedFormData($request, $form);

        DB::transaction(function () use ($data, $form): void {
            $form->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
            ]);

            $this->syncQuestions($form, $data['questions']);
        });

        return redirect()->route('forms.show', $form)->with('status', 'Form updated successfully.');
    }

    public function archive(Form $form): RedirectResponse
    {
        $form = $this->ownedForm($form);

        if ($form->archived_at === null) {
            $form->update([
                'is_active' => false,
                'archived_at' => now(),
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Form archived. Existing responses are preserved.');
    }

    public function results(Form $form): View
    {
        $form = $this->ownedForm($form);
        $submissions = $form->submissions()
            ->where('status', 'verified')
            ->with('answers')
            ->latest()
            ->get();

        return view('forms.results', compact('form', 'submissions'));
    }

    public function export(Form $form): StreamedResponse
    {
        $form = $this->ownedForm($form);
        $submissions = $form->submissions()
            ->where('status', 'verified')
            ->with('answers')
            ->oldest()
            ->get();

        $columns = $submissions
            ->flatMap(fn (Submission $submission) => $submission->answers)
            ->unique(fn ($answer) => $answer->question_id.'|'.$answer->question_text_snapshot)
            ->values();

        $filename = Str::slug($form->title).'-responses.csv';

        return response()->streamDownload(function () use ($columns, $submissions): void {
            $output = fopen('php://output', 'w');

            if ($output === false) {
                throw new \RuntimeException('Unable to open the CSV output stream.');
            }

            fwrite($output, "\xEF\xBB\xBF");
            $this->writeCsvRow($output, [
                'Submission ID',
                'Respondent email',
                'Submitted at',
                ...$columns->map(fn ($answer) => sprintf('Q%s: %s', $answer->question_id, $answer->question_text_snapshot))->all(),
            ]);

            foreach ($submissions as $submission) {
                $answers = $submission->answers->keyBy(
                    fn ($answer) => $answer->question_id.'|'.$answer->question_text_snapshot
                );
                $row = [
                    $submission->id,
                    $submission->respondent_email,
                    $submission->created_at?->toDateTimeString() ?? '',
                ];

                foreach ($columns as $column) {
                    $answer = $answers->get($column->question_id.'|'.$column->question_text_snapshot);
                    $value = $answer?->answer_text ?? '';

                    if ($answer?->question_type_snapshot === 'checkbox' && $value !== '') {
                        $value = implode(', ', json_decode($value, true, 512, JSON_THROW_ON_ERROR));
                    }

                    $row[] = $value;
                }

                $this->writeCsvRow($output, $row);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function ownedForm(Form $form): Form
    {
        return auth()->user()->forms()->whereKey($form->getKey())->firstOrFail();
    }

    /**
     * @return array{title: string, description?: string|null, questions: list<array<string, mixed>>}
     */
    private function validatedFormData(Request $request, ?Form $form = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'questions' => ['required', 'array', 'min:1', 'max:100'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.question_text' => ['required', 'string', 'max:2000'],
            'questions.*.type' => ['required', Rule::in(self::QUESTION_TYPES)],
            'questions.*.options' => ['nullable', 'string', 'max:5000'],
            'questions.*.is_required' => ['sometimes', 'boolean'],
        ]);

        foreach ($data['questions'] as $index => &$question) {
            $optionLines = preg_split('/\r\n|\r|\n/', $question['options'] ?? '') ?: [];
            $options = array_values(array_unique(array_filter(
                array_map('trim', $optionLines),
                static fn (string $option): bool => $option !== ''
            )));
            $isChoiceQuestion = in_array($question['type'], ['select', 'radio', 'checkbox'], true);

            if ($isChoiceQuestion && $options === []) {
                throw ValidationException::withMessages([
                    "questions.$index.options" => 'Add at least one choice for this question.',
                ]);
            }

            if (count($options) > 100 || collect($options)->contains(fn (string $option) => mb_strlen($option) > 255)) {
                throw ValidationException::withMessages([
                    "questions.$index.options" => 'Choices must be at most 255 characters each, with no more than 100 choices.',
                ]);
            }

            if (isset($question['id'])) {
                if ($form === null || ! $form->questions()->whereKey($question['id'])->exists()) {
                    throw ValidationException::withMessages([
                        "questions.$index.id" => 'This question does not belong to this form.',
                    ]);
                }
            }

            $question['options'] = $isChoiceQuestion ? $options : null;
            $question['is_required'] = (bool) ($question['is_required'] ?? false);
        }
        unset($question);

        return $data;
    }

    /**
     * @param  list<array<string, mixed>>  $questions
     */
    private function syncQuestions(Form $form, array $questions): void
    {
        $activeIds = [];

        foreach ($questions as $index => $data) {
            $attributes = [
                'question_text' => $data['question_text'],
                'type' => $data['type'],
                'options' => $data['options'],
                'is_required' => $data['is_required'],
                'is_active' => true,
                'order_index' => $index,
            ];

            if (isset($data['id'])) {
                $question = $form->questions()->whereKey($data['id'])->firstOrFail();
                $question->update($attributes);
            } else {
                $question = $form->questions()->create($attributes);
            }

            $activeIds[] = $question->id;
        }

        $form->questions()->whereNotIn('id', $activeIds)->update(['is_active' => false]);
    }

    /**
     * @param  resource  $output
     * @param  list<string|int>  $row
     */
    private function writeCsvRow($output, array $row): void
    {
        $safeRow = array_map(static function (string|int $value): string {
            $value = (string) $value;

            return preg_match('/^\s*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
        }, $row);

        fputcsv($output, $safeRow, ',', '"', '');
    }
}
