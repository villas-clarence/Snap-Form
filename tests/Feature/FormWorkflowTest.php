<?php

namespace Tests\Feature;

use App\Mail\VerifySubmissionEmail;
use App\Models\Answer;
use App\Models\Form;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FormWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_and_edit_a_form(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);

        $this->post(route('forms.store'), [
            'title' => 'Workshop signup',
            'description' => 'Tell us about yourself.',
            'questions' => [
                [
                    'question_text' => 'Name',
                    'type' => 'text',
                    'is_required' => '1',
                ],
                [
                    'question_text' => 'Session',
                    'type' => 'radio',
                    'options' => "Morning\nAfternoon",
                ],
            ],
        ])->assertRedirect(route('dashboard'));

        $form = Form::query()->where('title', 'Workshop signup')->firstOrFail();
        $this->assertSame($owner->id, $form->user_id);
        $this->assertSame(['Morning', 'Afternoon'], $form->questions[1]->options);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Workshop signup')
            ->assertDontSee('<svg viewBox="0 0 316 316"', false);
        $this->get(route('forms.show', $form))->assertOk()->assertSee('Session');
        $this->get(route('forms.edit', $form))
            ->assertOk()
            ->assertDontSee('Dashboard')
            ->assertDontSee('<svg viewBox="0 0 316 316"', false)
            ->assertSee('Workshop signup')
            ->assertSee('Short answer')
            ->assertSee('Paragraph')
            ->assertSee('Multiple choice')
            ->assertSee('Checkboxes')
            ->assertSee('Dropdown')
            ->assertSee('value="radio" selected', false);
        $this->get(route('forms.results', $form))->assertOk()->assertSee('No verified responses yet.');

        $firstQuestion = $form->questions[0];
        $this->put(route('forms.update', $form), [
            'title' => 'Updated workshop signup',
            'questions' => [
                [
                    'id' => $firstQuestion->id,
                    'question_text' => 'Your full name',
                    'type' => 'text',
                    'is_required' => '1',
                ],
            ],
        ])->assertRedirect(route('forms.show', $form));

        $this->assertSame('Your full name', $firstQuestion->fresh()->question_text);
        $this->assertFalse($form->questions()->whereKey($form->questions[1]->id)->first()->is_active);
    }

    public function test_form_builder_and_public_form_pages_render(): void
    {
        $owner = User::factory()->create();
        $form = Form::factory()->for($owner)->create();
        $form->questions()->create([
            'question_text' => 'What should we improve?',
            'type' => 'textarea',
        ]);

        $this->actingAs($owner)
            ->get(route('forms.create'))
            ->assertOk()
            ->assertDontSee('Dashboard')
            ->assertDontSee('<svg viewBox="0 0 316 316"', false)
            ->assertSee('Create a form');

        $this->get(route('public.show', $form->slug))
            ->assertOk()
            ->assertSee('What should we improve?')
            ->assertSee('Your email address');
    }

    public function test_checkbox_and_dropdown_questions_can_be_created_and_answered(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)
            ->post(route('forms.store'), [
                'title' => 'Event preferences',
                'questions' => [
                    [
                        'question_text' => 'Which sessions interest you?',
                        'type' => 'checkbox',
                        'options' => "Design\nEngineering",
                    ],
                    [
                        'question_text' => 'Preferred time',
                        'type' => 'select',
                        'options' => "Morning\nAfternoon",
                    ],
                ],
            ])
            ->assertRedirect(route('dashboard'));

        $form = Form::query()->where('title', 'Event preferences')->firstOrFail();
        $this->assertSame(['checkbox', 'select'], $form->questions->pluck('type')->all());

        $this->get(route('public.show', $form->slug))
            ->assertOk()
            ->assertSee('type="checkbox"', false)
            ->assertSee('<select', false)
            ->assertSee('Design')
            ->assertSee('Engineering')
            ->assertSee('Morning')
            ->assertSee('Afternoon');
    }

    public function test_owner_cannot_view_another_users_form(): void
    {
        $form = Form::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('forms.show', $form))
            ->assertNotFound();
    }

    public function test_public_response_requires_valid_answers_and_verified_email(): void
    {
        Mail::fake();

        $form = Form::factory()->create();
        $question = $form->questions()->create([
            'question_text' => 'Choose a session',
            'type' => 'radio',
            'options' => ['Morning', 'Afternoon'],
            'is_required' => true,
        ]);

        $this->post(route('public.send-verification', $form->slug), [
            'respondent_email' => 'respondent@example.com',
            'answers' => [$question->id => 'Evening'],
        ])->assertSessionHasErrors("answers.$question->id");

        $this->assertDatabaseCount('submissions', 0);

        $this->post(route('public.send-verification', $form->slug), [
            'respondent_email' => 'respondent@example.com',
            'answers' => [$question->id => 'Morning'],
        ])->assertOk()->assertViewIs('forms.pending');

        $submission = Submission::query()->firstOrFail();
        $this->assertSame('pending', $submission->status);
        $this->assertDatabaseHas('answers', [
            'submission_id' => $submission->id,
            'question_id' => $question->id,
            'answer_text' => 'Morning',
        ]);

        Mail::assertSent(VerifySubmissionEmail::class, function (VerifySubmissionEmail $mail) use ($form): bool {
            $this->get($mail->verificationUrl)
                ->assertOk()
                ->assertViewIs('forms.verified');
            $this->get($mail->verificationUrl)->assertStatus(410);

            return str_contains($mail->verificationUrl, $form->slug);
        });

        $this->assertDatabaseHas('submissions', [
            'id' => $submission->id,
            'status' => 'verified',
        ]);
    }

    public function test_response_cannot_include_an_answer_from_another_form(): void
    {
        Mail::fake();

        $form = Form::factory()->create();
        $otherForm = Form::factory()->create();
        $foreignQuestion = $otherForm->questions()->create([
            'question_text' => 'Other form question',
            'type' => 'text',
        ]);

        $this->post(route('public.send-verification', $form->slug), [
            'respondent_email' => 'respondent@example.com',
            'answers' => [$foreignQuestion->id => 'not allowed'],
        ])->assertSessionHasErrors("answers.$foreignQuestion->id");

        $this->assertDatabaseCount('submissions', 0);
    }

    public function test_archiving_a_form_preserves_responses_and_closes_the_public_link(): void
    {
        $owner = User::factory()->create();
        $form = Form::factory()->for($owner)->create();
        $submission = Submission::factory()->for($form)->create([
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($owner)
            ->post(route('forms.archive', $form))
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('submissions', ['id' => $submission->id]);
        $this->get(route('public.show', $form->slug))->assertNotFound();
    }

    public function test_csv_export_safely_escapes_formula_like_responses(): void
    {
        $owner = User::factory()->create();
        $form = Form::factory()->for($owner)->create(['title' => 'Workshop feedback']);
        $question = $form->questions()->create([
            'question_text' => 'Your answer',
            'type' => 'text',
        ]);
        $submission = Submission::factory()->for($form)->create([
            'respondent_email' => 'respondent@example.com',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);
        Answer::query()->create([
            'submission_id' => $submission->id,
            'question_id' => $question->id,
            'question_text_snapshot' => $question->question_text,
            'question_type_snapshot' => 'text',
            'answer_text' => '=2+2',
        ]);

        $response = $this->actingAs($owner)->get(route('forms.export', $form));

        $response->assertOk()->assertDownload('workshop-feedback-responses.csv');
        $this->assertStringContainsString("'=2+2", $response->streamedContent());
    }
}
