<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TopicSeeder::class,
            QuestionSeeder::class,
            RevisionNotesSeeder::class,
            MockPapersSeeder::class,
            AdminSeeder::class,
            PlanSeeder::class,
            UserSeeder::class,
            CouponSeeder::class,
            SubscriptionSeeder::class,
            QuestionAttemptSeeder::class,
        ]);
    }
}
