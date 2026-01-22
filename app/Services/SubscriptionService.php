<?php

namespace App\Services;

use App\Mail\SubscriptionActivated;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session;

class SubscriptionService
{
    public function createFromStripeSession(Session $session): ?Subscription
    {
        $userId = $session->metadata['user_id'] ?? null;
        $planId = $session->metadata['plan_id'] ?? null;

        if (!$userId || !$planId) {
            return null;
        }

        $user = User::find($userId);
        $plan = Plan::find($planId);

        if (!$user || !$plan) {
            return null;
        }

        $existing = Subscription::where('stripe_session_id', $session->id)->first();
        if ($existing) {
            return $existing;
        }

        [$start, $expires] = $this->resolveDates($user, $plan);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'auto_renew' => false,
            'started_at' => $start,
            'expires_at' => $expires,
            'stripe_session_id' => $session->id,
            'stripe_payment_intent' => $session->payment_intent ?? null,
            'stripe_customer_id' => $session->customer ?? null,
        ]);

        $this->sendActivationEmail($subscription);

        return $subscription;
    }

    public function createManualSubscription(User $user, Plan $plan): Subscription
    {
        [$start, $expires] = $this->resolveDates($user, $plan);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'auto_renew' => false,
            'started_at' => $start,
            'expires_at' => $expires,
        ]);

        $this->sendActivationEmail($subscription);

        return $subscription;
    }

    private function resolveDates(User $user, Plan $plan): array
    {
        $start = Carbon::now();
        $active = $user->activeSubscription();
        if ($active && $active->expires_at->greaterThan($start)) {
            $start = $active->expires_at->copy();
        }

        $expires = $start->copy()->addMonths($plan->duration_months);

        return [$start, $expires];
    }

    private function sendActivationEmail(Subscription $subscription): void
    {
        try {
            Mail::to($subscription->user->email)->send(new SubscriptionActivated($subscription));
        } catch (\Throwable $e) {
            // Ignore email failures in local/dev.
        }
    }
}
