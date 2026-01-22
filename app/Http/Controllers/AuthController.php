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

        $plans = $this->activePlans()->map(function (Plan $plan) {
            $days = max(1, $plan->duration_months * 30);
            $perDayPence = (int) round($plan->price_cents / $days);
            $perDay = $perDayPence >= 100
                ? 'GBP '.number_format($perDayPence / 100, 2).' per day'
                : $perDayPence.'p per day';

            $plan->price_gbp = number_format($plan->price_cents / 100, 2);
            $plan->label = $plan->duration_months.'-month access';
            $plan->per_day = $perDay;

            return $plan;
        });

        return view('auth.register', compact('plans'));
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
            ->whereIn('duration_months', [1, 3, 6])
            ->orderBy('duration_months')
            ->get();
    }
}
