<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => '1 Month', 'duration_months' => 1, 'price_cents' => 1999],
            ['name' => '3 Months', 'duration_months' => 3, 'price_cents' => 2997],
            ['name' => '6 Months', 'duration_months' => 6, 'price_cents' => 3597],
            ['name' => '12 Months', 'duration_months' => 12, 'price_cents' => 5497],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
