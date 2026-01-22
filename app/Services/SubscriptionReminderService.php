<?php

namespace App\Services;

use App\Mail\SubscriptionExpiringSoon;
use App\Models\Subscription;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SubscriptionReminderService
{
    public function sendExpiryReminders(int $days = 7): int
    {
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->addDays($days)->endOfDay();

        $subscriptions = Subscription::with(['user', 'plan'])
            ->where('status', 'active')
            ->whereNull('expiry_reminded_at')
            ->whereBetween('expires_at', [$start, $end])
            ->whereHas('user', function ($query) {
                $query->where('is_admin', false);
            })
            ->get();

        foreach ($subscriptions as $subscription) {
            if (!$subscription->user?->email) {
                continue;
            }

            $daysLeft = max(0, $start->diffInDays($subscription->expires_at, false));
            Mail::to($subscription->user->email)
                ->send(new SubscriptionExpiringSoon($subscription, max(1, $daysLeft)));

            $subscription->update([
                'expiry_reminded_at' => now(),
            ]);
        }

        return $subscriptions->count();
    }
}
