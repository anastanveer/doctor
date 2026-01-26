<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Services\StripeCheckoutService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->hasActiveSubscription()
                ? redirect()->route('dashboard')
                : redirect()->route('subscribe');
        }

        return view('auth.login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->hasActiveSubscription()
                ? redirect()->route('dashboard')
                : redirect()->route('subscribe');
        }

        $plans = $this->decoratePlans($this->activePlans());
        $plansByExam = $plans->groupBy('exam_type');
        $defaultPlanId = $this->defaultPlanId($plans);
        $examTypes = [
            Plan::EXAM_PRIMARY => 'MRCEM Primary',
            Plan::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];
        $canAccessIntermediate = false;

        return view('auth.register', compact('plansByExam', 'defaultPlanId', 'examTypes', 'canAccessIntermediate'));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }

        $request->session()->regenerate();

        return Auth::user()->hasActiveSubscription()
            ? redirect()->route('dashboard')
            : redirect()->route('subscribe');
    }

    public function register(Request $request, StripeCheckoutService $stripe, SubscriptionService $subscriptions): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'plan_id' => ['required', 'exists:plans,id'],
            'terms' => ['accepted'],
        ]);

        $plan = Plan::find($data['plan_id']);
        if ($plan && $plan->exam_type === Plan::EXAM_INTERMEDIATE) {
            return back()->withErrors([
                'plan_id' => 'MRCEM Intermediate unlocks after completing all MRCEM Primary MCQs.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        try {
            $sessionUrl = $stripe->createCheckoutSession($user, $data['plan_id']);
        } catch (\Throwable $e) {
            $plan = Plan::find($data['plan_id']);
            if ($plan) {
                $subscriptions->createManualSubscription($user, $plan);
            }

            return redirect()->route('checkout.success');
        }

        return redirect()->away($sessionUrl);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function activePlans()
    {
        return Plan::where('is_active', true)
            ->where('name', 'like', 'MRCEM %')
            ->whereIn('duration_months', [1, 3, 6])
            ->orderBy('duration_months')
            ->get();
    }

    private function decoratePlans($plans)
    {
        return $plans->map(function (Plan $plan) {
            $days = max(1, $plan->duration_months * 30);
            $perDayPence = (int) round($plan->price_cents / $days);
            $perDay = $perDayPence >= 100
                ? 'GBP '.number_format($perDayPence / 100, 2).' per day'
                : $perDayPence.'p per day';

            $plan->price_gbp = number_format($plan->price_cents / 100, 2);
            $plan->exam_label = $plan->examLabel();
            $plan->label = $plan->duration_months.'-month access';
            $plan->display_label = $plan->exam_label.' • '.$plan->label;
            $plan->per_day = $perDay;

            return $plan;
        });
    }

    private function defaultPlanId($plans): ?int
    {
        $preferred = $plans->first(function (Plan $plan) {
            return $plan->exam_type === Plan::EXAM_PRIMARY && $plan->duration_months === 3;
        });

        return $preferred?->id ?? $plans->first()?->id;
    }
}
