<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\QuestionAttempt;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $usersCount = User::where('is_admin', false)->count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $expiringSoon = Subscription::where('status', 'active')
            ->whereBetween('expires_at', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();
        $plans = Plan::withCount('subscriptions')->get();
        $coupons = Coupon::latest()->take(5)->get();

        $currentWeek = User::where('is_admin', false)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $previousWeek = User::where('is_admin', false)
            ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->count();
        $weeklyGrowth = $previousWeek > 0
            ? (int) round((($currentWeek - $previousWeek) / $previousWeek) * 100)
            : ($currentWeek > 0 ? 100 : 0);

        $revenue = Subscription::with('plan')
            ->where('status', 'active')
            ->get()
            ->sum(fn ($sub) => $sub->plan?->price_cents ?? 0);

        $signups = collect(range(6, 0))->map(function ($days) {
            $date = Carbon::now()->subDays($days);
            return [
                'label' => $date->format('D'),
                'value' => User::where('is_admin', false)
                    ->whereDate('created_at', $date->toDateString())
                    ->count(),
            ];
        });

        $accuracy = collect(range(1, 7))->map(function ($point) {
            $weekStart = Carbon::now()->subWeeks(7 - $point)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $attempts = QuestionAttempt::query()
                ->join('users', 'question_attempts.user_id', '=', 'users.id')
                ->where('users.is_admin', false)
                ->whereBetween('answered_at', [$weekStart, $weekEnd])
                ->get();
            $value = $attempts->isNotEmpty()
                ? (int) round($attempts->avg('is_correct') * 100)
                : 0;

            return [
                'label' => "W{$point}",
                'value' => $value,
            ];
        });

        $recentUsers = User::where('is_admin', false)->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'activeSubscriptions',
            'expiringSoon',
            'revenue',
            'weeklyGrowth',
            'plans',
            'coupons',
            'signups',
            'accuracy',
            'recentUsers'
        ));
    }
}
