<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $plans = Plan::all();
        $coupon = Coupon::first();

        User::where('is_admin', false)->get()->each(function (User $user) use ($plans, $coupon) {
            $plan = $plans->random();
            $start = Carbon::now()->subDays(rand(5, 40));
            $expires = (clone $start)->addMonths($plan->duration_months);
            $status = $expires->isPast() ? 'expired' : 'active';

            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'coupon_id' => rand(0, 1) ? $coupon?->id : null,
                'status' => $status,
                'auto_renew' => (bool) rand(0, 1),
                'started_at' => $start,
                'expires_at' => $expires,
            ]);
        });
    }
}
