<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockPaper;
use App\Models\MockPaperOption;
use App\Models\MockPaperQuestion;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MockPaperQuestionController extends Controller
{
    public function index(Request $request): View
    {
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = null;
        }

        $papers = MockPaper::when($examType, fn ($query) => $query->where('exam_type', $examType))
            ->orderBy('order')
            ->get();
        $paperId = $request->filled('paper') ? (int) $request->input('paper') : null;
        if ($paperId && !$papers->contains('id', $paperId)) {
            $paperId = null;
        }

        $questions = MockPaperQuestion::with('paper')
            ->when($paperId, fn ($query) => $query->where('mock_paper_id', $paperId))
            ->when($examType, function ($query) use ($examType) {
                $query->whereHas('paper', fn ($subquery) => $subquery->where('exam_type', $examType));
            })
            ->orderBy('mock_paper_id')
            ->orderBy('order')
            ->paginate(15);

        return view('admin.mock-questions.index', compact('questions', 'papers', 'paperId', 'examTypes', 'examType'));
    }

    public function create(Request $request): View
    {
        $papers = MockPaper::orderBy('order')->get();
        $examTypes = $this->examTypes();
        $examType = $request->input('exam_type');
        if (!array_key_exists($examType, $examTypes)) {
            $examType = null;
        }
        $selectedPaper = $request->filled('paper') ? (int) $request->input('paper') : null;
        $selectedPaperModel = $selectedPaper ? $papers->firstWhere('id', $selectedPaper) : null;
        if ($selectedPaperModel && !$examType) {
            $examType = $selectedPaperModel->exam_type;
        }
        if (!$examType) {
            $examType = Topic::EXAM_PRIMARY;
        }

        return view('admin.mock-questions.form', [
            'question' => new MockPaperQuestion(),
            'papers' => $papers,
            'options' => collect(),
            'selectedPaper' => $selectedPaper,
            'correctIndex' => 0,
            'examTypes' => $examTypes,
            'examType' => $examType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $question = MockPaperQuestion::create($data);

        $this->syncOptions($question, $request->input('options', []), $request->input('correct_option'));

        return redirect()->route('admin.mock-questions.index')
            ->with('status', 'Mock question created.');
    }

    public function edit(MockPaperQuestion $mockQuestion): View
    {
        $papers = MockPaper::orderBy('order')->get();
        $options = $mockQuestion->options()->orderBy('order')->get();
        $correctIndex = $options->search(fn ($option) => (bool) $option->is_correct);
        $examType = $mockQuestion->paper?->exam_type ?? Topic::EXAM_PRIMARY;

        return view('admin.mock-questions.form', [
            'question' => $mockQuestion,
            'papers' => $papers,
            'options' => $options,
            'selectedPaper' => $mockQuestion->mock_paper_id,
            'correctIndex' => $correctIndex !== false ? $correctIndex : 0,
            'examTypes' => $this->examTypes(),
            'examType' => $examType,
        ]);
    }

    public function update(Request $request, MockPaperQuestion $mockQuestion): RedirectResponse
    {
        $data = $this->validateQuestion($request);
        $mockQuestion->update($data);

        $mockQuestion->options()->delete();
        $this->syncOptions($mockQuestion, $request->input('options', []), $request->input('correct_option'));

        return redirect()->route('admin.mock-questions.index')
            ->with('status', 'Mock question updated.');
    }

    public function destroy(MockPaperQuestion $mockQuestion): RedirectResponse
    {
        $mockQuestion->delete();

        return redirect()->route('admin.mock-questions.index')
            ->with('status', 'Mock question deleted.');
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'mock_paper_id' => ['required', 'exists:mock_papers,id'],
            'topic' => ['nullable', 'string', 'max:120'],
            'stem' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function syncOptions(MockPaperQuestion $question, array $options, $correctIndex): void
    {
        $correctIndex = is_numeric($correctIndex) ? (int) $correctIndex : null;
        $order = 1;

        foreach ($options as $index => $option) {
            $text = trim((string) ($option['text'] ?? ''));
            if ($text === '') {
                continue;
            }

            MockPaperOption::create([
                'mock_paper_question_id' => $question->id,
                'text' => $text,
                'is_correct' => $correctIndex !== null && $index === $correctIndex,
                'order' => $option['order'] ?? $order,
            ]);
            $order++;
        }
    }

    private function examTypes(): array
    {
        return [
            Topic::EXAM_PRIMARY => 'MRCEM Primary',
            Topic::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];
    }
}
