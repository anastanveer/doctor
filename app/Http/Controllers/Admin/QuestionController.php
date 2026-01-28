<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
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
        $examType = request('exam_type');
        $questions = Question::with('topic')
            ->when($examType, fn ($query) => $query->where('exam_type', $examType))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $examTypes = [
            Topic::EXAM_PRIMARY => 'MRCEM Primary',
            Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];

        return view('admin.questions.index', compact('questions', 'examTypes', 'examType'));
    }

    public function create(): View
    {
        $examType = request('exam_type', Topic::EXAM_PRIMARY);
        $topics = Topic::orderBy('name')->get();
        $types = $this->types;

        return view('admin.questions.form', [
            'question' => new Question(),
            'topics' => $topics,
            'types' => $types,
            'options' => collect(),
            'examTypes' => [
                Topic::EXAM_PRIMARY => 'MRCEM Primary',
                Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
            ],
            'examType' => $examType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $data['meta'] = $this->buildMeta($request);
        $this->stripUploadFields($data);
        $question = Question::create($data);

        $this->syncOptions($question, $request->input('options', []));

        return redirect()->route('admin.questions.index')
            ->with('status', 'Question created.');
    }

    public function edit(Question $question): View
    {
        $examType = $question->exam_type ?? $question->topic?->exam_type ?? Topic::EXAM_PRIMARY;
        $topics = Topic::orderBy('name')->get();
        $types = $this->types;
        $options = $question->options()->get();

        return view('admin.questions.form', [
            'question' => $question,
            'topics' => $topics,
            'types' => $types,
            'options' => $options,
            'examTypes' => [
                Topic::EXAM_PRIMARY => 'MRCEM Primary',
                Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
            ],
            'examType' => $examType,
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $data['meta'] = $this->buildMeta($request, $question);
        $this->stripUploadFields($data);
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
        $data = $request->validate([
            'topic_id' => ['nullable', 'exists:topics,id'],
            'exam_type' => ['required', Rule::in(Topic::EXAM_TYPES)],
            'type' => ['required', 'string'],
            'difficulty' => ['nullable', 'string', 'max:50'],
            'stem' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'question_image' => ['nullable', 'image', 'max:5120'],
            'question_image_alt' => ['nullable', 'string', 'max:160'],
            'explanation_image' => ['nullable', 'image', 'max:5120'],
            'explanation_image_alt' => ['nullable', 'string', 'max:160'],
            'remove_question_image' => ['nullable', 'boolean'],
            'remove_explanation_image' => ['nullable', 'boolean'],
            'answer_text' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'shuffle_options' => ['nullable', 'boolean'],
            'time_limit' => ['nullable', 'integer', 'min:0'],
        ]);

        if (!empty($data['topic_id'])) {
            $topicExam = Topic::query()
                ->whereKey($data['topic_id'])
                ->value('exam_type');
            if ($topicExam) {
                $data['exam_type'] = $topicExam;
            }
        }

        return $data;
    }

    private function buildMeta(Request $request, ?Question $question = null): array
    {
        $meta = is_array($question?->meta) ? $question->meta : [];

        if ($request->boolean('remove_question_image')) {
            $this->deleteStoredImage($meta['image'] ?? null);
            unset($meta['image'], $meta['image_alt']);
        }

        if ($request->hasFile('question_image')) {
            $this->deleteStoredImage($meta['image'] ?? null);
            $path = $request->file('question_image')->store('questions', 'public');
            $meta['image'] = Storage::url($path);
        }

        $questionAlt = trim((string) $request->input('question_image_alt', ''));
        $meta['image_alt'] = $questionAlt !== '' ? $questionAlt : null;

        if ($request->boolean('remove_explanation_image')) {
            $this->deleteStoredImage($meta['explanation_image'] ?? null);
            unset($meta['explanation_image'], $meta['explanation_image_alt']);
        }

        if ($request->hasFile('explanation_image')) {
            $this->deleteStoredImage($meta['explanation_image'] ?? null);
            $path = $request->file('explanation_image')->store('questions', 'public');
            $meta['explanation_image'] = Storage::url($path);
        }

        $explanationAlt = trim((string) $request->input('explanation_image_alt', ''));
        $meta['explanation_image_alt'] = $explanationAlt !== '' ? $explanationAlt : null;

        return $meta;
    }

    private function deleteStoredImage(?string $url): void
    {
        if (!$url || !is_string($url)) {
            return;
        }
        if (str_starts_with($url, '/storage/')) {
            $path = substr($url, strlen('/storage/'));
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function stripUploadFields(array &$data): void
    {
        unset(
            $data['question_image'],
            $data['question_image_alt'],
            $data['explanation_image'],
            $data['explanation_image_alt'],
            $data['remove_question_image'],
            $data['remove_explanation_image']
        );
    }

    private function syncOptions(Question $question, array $options): void
    {
        $order = 1;
        $singleCorrect = in_array($question->type, ['single', 'true_false'], true);
        $allowMultiple = $question->type === 'multiple';
        $correctAssigned = false;
        foreach ($options as $option) {
            $text = trim((string) ($option['text'] ?? ''));
            if ($text === '') {
                continue;
            }

            $isCorrect = isset($option['is_correct']);
            if ($singleCorrect) {
                $isCorrect = $isCorrect && !$correctAssigned;
                if ($isCorrect) {
                    $correctAssigned = true;
                }
            } elseif (!$allowMultiple) {
                $isCorrect = false;
            }

            QuestionOption::create([
                'question_id' => $question->id,
                'text' => $text,
                'is_correct' => $isCorrect,
                'order' => $option['order'] ?? $order,
                'match_key' => $option['match_key'] ?? null,
                'correct_order' => $option['correct_order'] ?? null,
            ]);
            $order++;
        }
    }
}
