<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeCheckoutService
{
    public function createCheckoutSession(User $user, int $planId): string
    {
        $plan = Plan::findOrFail($planId);

        if (!class_exists(\Stripe\Stripe::class) || !class_exists(\Stripe\Checkout\Session::class)) {
            throw new \RuntimeException('Stripe SDK is not installed.');
        }

        $secret = config('services.stripe.secret');
        if (!$secret) {
            throw new \RuntimeException('Stripe secret key is missing.');
        }

        Stripe::setApiKey($secret);

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($plan->currency ?? 'GBP'),
                    'product_data' => [
                        'name' => $plan->name.' access',
                    ],
                    'unit_amount' => $plan->price_cents,
                ],
                'quantity' => 1,
            ]],
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => (string) $user->id,
                'plan_id' => (string) $plan->id,
            ],
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        return $session->url;
    }
}
