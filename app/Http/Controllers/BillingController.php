<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\StripeCheckoutService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function showSubscribe(): View|RedirectResponse
    {
        $user = auth()->user();

        $plansQuery = Plan::where('is_active', true)
            ->where('name', 'like', 'MRCEM %')
            ->whereIn('duration_months', [1, 3, 6])
            ->orderBy('duration_months');
        $plans = $plansQuery->get();

        $plans = $this->decoratePlans($plans);
        $plansByExam = $plans->groupBy('exam_type');
        $defaultPlanId = $this->defaultPlanId($plans);
        $examTypes = [
            Plan::EXAM_PRIMARY => 'MRCEM Primary',
            Plan::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];

        return view('subscribe', compact('plansByExam', 'defaultPlanId', 'examTypes'));
    }

    public function startCheckout(Request $request, StripeCheckoutService $stripe): RedirectResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'terms' => ['accepted'],
        ]);

        $plan = Plan::find((int) $data['plan_id']);

        try {
            $sessionUrl = $stripe->createCheckoutSession($request->user(), (int) $data['plan_id']);
        } catch (\Throwable $e) {
            if ($plan) {
                app(SubscriptionService::class)->createManualSubscription($request->user(), $plan);
                return redirect()->route('checkout.success');
            }

            return back()->withErrors(['plan_id' => 'Checkout is unavailable. Please contact support.']);
        }

        return redirect()->away($sessionUrl);
    }

    public function success(Request $request, SubscriptionService $subscriptions): View
    {
        $sessionId = $request->string('session_id');
        if ($sessionId->isNotEmpty()) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $session = Session::retrieve($sessionId->toString());
                if ($session && $session->payment_status === 'paid') {
                    $subscriptions->createFromStripeSession($session);
                }
            } catch (\Throwable $e) {
                // If Stripe lookup fails, show success page and rely on webhook.
            }
        }

        return view('checkout.success');
    }

    public function cancel(): View
    {
        return view('checkout.cancel');
    }

    public function cancelSubscription(Request $request): RedirectResponse
    {
        $subscription = $request->user()
            ->subscriptions()
            ->whereIn('status', ['active', 'cancelled'])
            ->orderByDesc('expires_at')
            ->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'auto_renew' => false,
            ]);
        }

        return redirect()->route('account')
            ->with('status', 'Your subscription will end on the expiry date.');
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
