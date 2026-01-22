<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            ['code' => 'REV20', 'type' => 'percent', 'value' => 20, 'max_uses' => 200, 'expires_at' => Carbon::now()->addDays(30)],
            ['code' => 'EARLY10', 'type' => 'percent', 'value' => 10, 'max_uses' => 100, 'expires_at' => Carbon::now()->addDays(14)],
            ['code' => 'SAVE5', 'type' => 'fixed', 'value' => 500, 'max_uses' => 50, 'expires_at' => Carbon::now()->addDays(7)],
        ];

        foreach ($coupons as $coupon) {
            Coupon::firstOrCreate(['code' => $coupon['code']], $coupon);
        }
    }
}
