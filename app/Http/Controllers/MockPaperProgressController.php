<?php

namespace App\Http\Controllers;

use App\Models\MockPaper;
use App\Models\MockPaperProgress;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MockPaperProgressController extends Controller
{
    public function store(Request $request, MockPaper $mockPaper): JsonResponse
    {
        $user = $request->user();
        $examType = $this->resolveExamType($user);
        if (!$mockPaper->is_active || $mockPaper->exam_type !== $examType) {
            abort(404);
        }

        $data = $request->validate([
            'active_index' => ['nullable', 'integer', 'min:0'],
            'state' => ['nullable', 'array'],
            'clear' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['clear'])) {
            MockPaperProgress::where('user_id', $user->id)
                ->where('mock_paper_id', $mockPaper->id)
                ->delete();

            return response()->json(['status' => 'cleared']);
        }

        $totalQuestions = $mockPaper->questions()->count();
        $activeIndex = (int) ($data['active_index'] ?? 0);
        if ($totalQuestions > 0) {
            $activeIndex = min(max($activeIndex, 0), $totalQuestions - 1);
        }

        MockPaperProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'mock_paper_id' => $mockPaper->id,
            ],
            [
                'active_index' => $activeIndex,
                'state' => $data['state'] ?? [],
            ]
        );

        return response()->json(['status' => 'saved']);
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
