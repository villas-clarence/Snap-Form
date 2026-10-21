<?php

namespace App\Http\Controllers;

use App\Mail\VerifySubmissionEmail;
use App\Models\EmailVerificationToken;
use App\Models\Form;
use App\Models\Question;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PublicFormController extends Controller
{
    public function show(string $slug): View
    {
        $form = $this->activeForm($slug);

        return view('forms.public', compact('form'));
    }

    public function sendVerification(Request $request, string $slug): View
    {
        $form = $this->activeForm($slug);
        $validated = $request->validate([
            'respondent_email' => ['required', 'email', 'max:255'],
            'answers' => ['nullable', 'array'],
        ]);
        $questions = $form->activeQuestions()->get();
        $answers = $validated['answers'] ?? [];
        $this->validateAnswers($questions, $answers);

        $plainToken = Str::random(64);
        $submission = DB::transaction(function () use ($answers, $form, $plainToken, $validated, $questions): Submission {
            $submission = $form->submissions()->create([
                'respondent_email' => $validated['respondent_email'],
                'status' => 'pending',
            ]);

            foreach ($questions as $question) {
                $value = $answers[$question->id] ?? $answers[(string) $question->id] ?? null;

                if ($value === null || $value === '') {
                    continue;
                }

                if ($question->type === 'checkbox') {
                    $value = json_encode($value, JSON_THROW_ON_ERROR);
                }

                $submission->answers()->create([
                    'question_id' => $question->id,
                    'question_text_snapshot' => $question->question_text,
                    'question_type_snapshot' => $question->type,
                    'options_snapshot' => $question->options,
                    'answer_text' => $value,
                ]);
            }

            $submission->verificationToken()->create([
                'token_hash' => hash('sha256', $plainToken),
                'expires_at' => now()->addMinutes(30),
            ]);

            return $submission;
        });

        $verificationPath = route('public.verify', [
            'slug' => $form->slug,
            'token' => $plainToken,
        ], absolute: false);
        $verificationUrl = rtrim(config('app.url'), '/').$verificationPath;

        Mail::to($submission->respondent_email)->send(
            new VerifySubmissionEmail($form->title, $verificationUrl)
        );

        return view('forms.pending', [
            'form' => $form,
            'email' => $submission->respondent_email,
        ]);
    }

    public function verifyEmail(string $slug, string $token): View|Response
    {
        $verification = EmailVerificationToken::query()
            ->with('submission.form')
            ->where('token_hash', hash('sha256', $token))
            ->first();

        if (
            $verification === null
            || $verification->submission->form->slug !== $slug
            || $verification->used_at !== null
            || $verification->expires_at->isPast()
            || $verification->submission->status !== 'pending'
        ) {
            return response()->view('forms.verification-failed', status: 410);
        }

        $verified = DB::transaction(function () use ($verification): bool {
            $lockedToken = EmailVerificationToken::query()
                ->whereKey($verification->id)
                ->lockForUpdate()
                ->first();

            if (
                $lockedToken === null
                || $lockedToken->used_at !== null
                || $lockedToken->expires_at->isPast()
            ) {
                return false;
            }

            $lockedToken->update(['used_at' => now()]);
            $lockedToken->submission()->update([
                'status' => 'verified',
                'email_verified_at' => now(),
            ]);

            return true;
        });

        if (! $verified) {
            return response()->view('forms.verification-failed', status: 410);
        }

        return view('forms.verified', [
            'form' => $verification->submission->form,
        ]);
    }

    private function activeForm(string $slug): Form
    {
        return Form::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->whereNull('archived_at')
            ->firstOrFail();
    }

    /**
     * @param  iterable<Question>  $questions
     * @param  array<int|string, mixed>  $answers
     */
    private function validateAnswers(iterable $questions, array $answers): void
    {
        $questions = collect($questions);
        $allowedIds = $questions->map(fn ($question) => (string) $question->id)->all();
        $errors = [];

        foreach (array_keys($answers) as $answerId) {
            if (! in_array((string) $answerId, $allowedIds, true)) {
                $errors["answers.$answerId"] = 'This question is not part of this form.';
            }
        }

        foreach ($questions as $question) {
            $key = (string) $question->id;
            $value = $answers[$question->id] ?? $answers[$key] ?? null;

            if ($value === null || $value === '' || $value === []) {
                if ($question->is_required) {
                    $errors["answers.$key"] = 'This question is required.';
                }

                continue;
            }

            if ($question->is_required && is_string($value) && trim($value) === '') {
                $errors["answers.$key"] = 'This question is required.';

                continue;
            }

            $valid = match ($question->type) {
                'text', 'textarea' => is_string($value)
                    && mb_strlen($value) <= ($question->type === 'textarea' ? 20000 : 10000),
                'select', 'radio' => is_string($value)
                    && in_array($value, $question->options ?? [], true),
                'checkbox' => is_array($value)
                    && count($value) <= count($question->options ?? [])
                    && count(array_unique($value, SORT_REGULAR)) === count($value)
                    && collect($value)->every(
                        fn ($choice) => is_string($choice) && in_array($choice, $question->options ?? [], true)
                    ),
                default => false,
            };

            if (! $valid) {
                $errors["answers.$key"] = 'Enter a valid answer for this question.';
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}
