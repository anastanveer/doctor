<?php

namespace App\Http\Controllers;

use App\Models\MockPaper;
use App\Models\MockPaperQuestion;
use Illuminate\View\View;

class MockPapersController extends Controller
{
    public function index(): View
    {
        $papers = MockPaper::where('is_active', true)
            ->orderBy('order')
            ->get();

        $totalMockQuestions = MockPaperQuestion::whereHas('paper', function ($query) {
            $query->where('is_active', true);
        })->count();
        $totalMockTopics = MockPaperQuestion::whereHas('paper', function ($query) {
            $query->where('is_active', true);
        })->distinct('topic')->count('topic');

        return view('mock-papers', [
            'papers' => $papers,
            'totalMockQuestions' => $totalMockQuestions,
            'totalMockTopics' => $totalMockTopics,
        ]);
    }

    public function show(MockPaper $mockPaper): View
    {
        if (!$mockPaper->is_active) {
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

        return view('mock-papers.show', [
            'paper' => $mockPaper,
            'paperTopics' => $paperTopics,
        ]);
    }
}
