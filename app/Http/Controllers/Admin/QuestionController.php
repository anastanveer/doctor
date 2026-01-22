<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    private array $types = [
        'single' => 'Single correct',
        'multiple' => 'Multiple correct',
        'true_false' => 'True / False',
        'ordering' => 'Ordering',
        'match' => 'Matching',
        'short_answer' => 'Short answer',
    ];

    public function index(): View
    {
        $questions = Question::with('topic')->latest()->paginate(15);

        return view('admin.questions.index', compact('questions'));
    }

    public function create(): View
    {
        $topics = Topic::orderBy('name')->get();
        $types = $this->types;

        return view('admin.questions.form', [
            'question' => new Question(),
            'topics' => $topics,
            'types' => $types,
            'options' => collect(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $question = Question::create($data);

        $this->syncOptions($question, $request->input('options', []));

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question created.');
    }

    public function edit(Question $question): View
    {
        $topics = Topic::orderBy('name')->get();
        $types = $this->types;
        $options = $question->options()->get();

        return view('admin.questions.form', compact('question', 'topics', 'types', 'options'));
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $question->update($data);

        $question->options()->delete();
        $this->syncOptions($question, $request->input('options', []));

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question updated.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question deleted.');
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'topic_id' => ['nullable', 'exists:topics,id'],
            'type' => ['required', 'string'],
            'difficulty' => ['nullable', 'string', 'max:50'],
            'stem' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'answer_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'shuffle_options' => ['nullable', 'boolean'],
            'time_limit' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function syncOptions(Question $question, array $options): void
    {
        $order = 1;
        foreach ($options as $option) {
            $text = trim((string) ($option['text'] ?? ''));
            if ($text === '') {
                continue;
            }

            QuestionOption::create([
                'question_id' => $question->id,
                'text' => $text,
                'is_correct' => isset($option['is_correct']),
                'order' => $option['order'] ?? $order,
                'match_key' => $option['match_key'] ?? null,
                'correct_order' => $option['correct_order'] ?? null,
            ]);
            $order++;
        }
    }
}
