<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\Subscription;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $primaryPlan = Plan::where('exam_type', Plan::EXAM_PRIMARY)
            ->where('duration_months', 3)
            ->first();
        $intermediatePlan = Plan::where('exam_type', Plan::EXAM_INTERMEDIATE)
            ->where('duration_months', 3)
            ->first();

        if (!$primaryPlan || !$intermediatePlan) {
            return;
        }

        $primaryUser = $this->createUser('primary.test@revisemrcem.local', 'Primary Test');
        $this->ensureSubscription($primaryUser, $primaryPlan);

        $intermediateUser = $this->createUser('intermediate.test@revisemrcem.local', 'Intermediate Test');
        $this->ensureSubscription($intermediateUser, $intermediatePlan);

        $passedUser = $this->createUser('primary.passed@revisemrcem.local', 'Primary Passed');
        $this->ensureSubscription($passedUser, $primaryPlan);
        $this->seedPrimaryCompletion($passedUser);

        $unlockUser = $this->createUser('unlock.ready@revisemrcem.local', 'Unlock Ready');
        $this->ensureSubscription($unlockUser, $primaryPlan);
        $this->seedPrimaryCompletion($unlockUser);
    }

    private function createUser(string $email, string $name): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('Password123!'),
                'is_admin' => false,
            ]
        );
    }

    private function ensureSubscription(User $user, Plan $plan): void
    {
        $exists = Subscription::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->whereIn('status', ['active', 'cancelled'])
            ->exists();

        if ($exists) {
            return;
        }

        $startedAt = now();
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'auto_renew' => true,
            'started_at' => $startedAt,
            'expires_at' => $startedAt->copy()->addMonths($plan->duration_months),
        ]);
    }

    private function seedPrimaryCompletion(User $user): void
    {
        $questions = Question::query()
            ->where('exam_type', Topic::EXAM_PRIMARY)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereHas('options')
                    ->orWhere('type', 'short_answer');
            })
            ->pluck('id');

        if ($questions->isEmpty()) {
            return;
        }

        $existing = QuestionAttempt::where('user_id', $user->id)
            ->pluck('question_id')
            ->flip();

        $now = now();
        $batch = [];
        foreach ($questions as $questionId) {
            if (isset($existing[$questionId])) {
                continue;
            }
            $batch[] = [
                'user_id' => $user->id,
                'question_id' => $questionId,
                'is_correct' => true,
                'time_seconds' => random_int(40, 120),
                'answered_at' => $now->copy()->subDays(random_int(0, 10)),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (count($batch) >= 200) {
                QuestionAttempt::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            QuestionAttempt::insert($batch);
        }
    }
}
