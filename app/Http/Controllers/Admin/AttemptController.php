<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionAttempt;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AttemptController extends Controller
{
    public function index(): View
    {
        $totalAttempts = QuestionAttempt::whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })->count();

        $accuracy = QuestionAttempt::whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })->avg('is_correct');
        $accuracy = $accuracy ? (int) round($accuracy * 100) : 0;

        $avgTime = QuestionAttempt::whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })->whereNotNull('time_seconds')->avg('time_seconds');
        $avgTime = $avgTime ? (int) round($avgTime) : 0;

        $activeUsers = QuestionAttempt::whereHas('user', function ($query) {
            $query->where('is_admin', false);
        })->where('answered_at', '>=', Carbon::now()->subDays(7))
            ->distinct('user_id')
            ->count('user_id');

        $userStats = QuestionAttempt::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_admin', false);
            })
            ->selectRaw('user_id, count(*) as attempts, avg(is_correct) as accuracy, avg(time_seconds) as avg_time, max(answered_at) as last_attempt')
            ->groupBy('user_id')
            ->orderByDesc('attempts')
            ->limit(12)
            ->get();

        $recentAttempts = QuestionAttempt::with(['user', 'question.topic'])
            ->whereHas('user', function ($query) {
                $query->where('is_admin', false);
            })
            ->orderByDesc('answered_at')
            ->paginate(12);

        return view('admin.attempts.index', compact(
            'totalAttempts',
            'accuracy',
            'avgTime',
            'activeUsers',
            'userStats',
            'recentAttempts'
        ));
    }
}
