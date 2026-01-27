<?php

namespace App\Http\Controllers;

use App\Models\MockPaper;
use App\Models\MockPaperProgress;
use App\Models\MockPaperQuestion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\View\View;

class MockPapersController extends Controller
{
    public function index(): View
    {
        $examType = $this->resolveExamType(auth()->user());
        $papers = MockPaper::where('is_active', true)
            ->where('exam_type', $examType)
            ->orderBy('order')
            ->get();

        $totalMockQuestions = MockPaperQuestion::whereHas('paper', function ($query) use ($examType) {
            $query->where('is_active', true)
                ->where('exam_type', $examType);
        })->count();
        $totalMockTopics = MockPaperQuestion::whereHas('paper', function ($query) use ($examType) {
            $query->where('is_active', true)
                ->where('exam_type', $examType);
        })->distinct('topic')->count('topic');

        return view('mock-papers', [
            'papers' => $papers,
            'totalMockQuestions' => $totalMockQuestions,
            'totalMockTopics' => $totalMockTopics,
        ]);
    }

    public function show(MockPaper $mockPaper): View
    {
        $examType = $this->resolveExamType(auth()->user());
        if (!$mockPaper->is_active || $mockPaper->exam_type !== $examType) {
            abort(404);
        }

        $questionLimit = 6;
        $mockPaper->load([
            'questions' => function ($query) use ($questionLimit) {
                $query->orderBy('order')
                    ->limit($questionLimit);
            },
            'questions.options' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        $paperTopics = $mockPaper->questions->pluck('topic')->filter()->unique()->count();
        $progress = MockPaperProgress::where('user_id', auth()->id())
            ->where('mock_paper_id', $mockPaper->id)
            ->first();

        return view('mock-papers.show', [
            'paper' => $mockPaper,
            'paperTopics' => $paperTopics,
            'progress' => $progress,
        ]);
    }

    private function resolveExamType(?User $user): string
    {
        $examType = $user?->activeSubscription()?->plan?->exam_type;
        if (in_array($examType, Topic::EXAM_TYPES, true)) {
            return $examType;
        }

        return Topic::EXAM_PRIMARY;
    }
}
