<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'exam_type' => Plan::EXAM_PRIMARY,
                'name' => 'MRCEM Primary - 1 Month',
                'duration_months' => 1,
                'price_cents' => 2500,
            ],
            [
                'exam_type' => Plan::EXAM_PRIMARY,
                'name' => 'MRCEM Primary - 3 Months',
                'duration_months' => 3,
                'price_cents' => 4500,
            ],
            [
                'exam_type' => Plan::EXAM_PRIMARY,
                'name' => 'MRCEM Primary - 6 Months',
                'duration_months' => 6,
                'price_cents' => 5500,
            ],
            [
                'exam_type' => Plan::EXAM_INTERMEDIATE,
                'name' => 'MRCEM Intermediate - 1 Month',
                'duration_months' => 1,
                'price_cents' => 2500,
            ],
            [
                'exam_type' => Plan::EXAM_INTERMEDIATE,
                'name' => 'MRCEM Intermediate - 3 Months',
                'duration_months' => 3,
                'price_cents' => 4500,
            ],
            [
                'exam_type' => Plan::EXAM_INTERMEDIATE,
                'name' => 'MRCEM Intermediate - 6 Months',
                'duration_months' => 6,
                'price_cents' => 5000,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
