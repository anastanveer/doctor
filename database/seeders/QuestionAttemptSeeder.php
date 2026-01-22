<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class QuestionAttemptSeeder extends Seeder
{
    public function run(): void
    {
        if (QuestionAttempt::exists()) {
            return;
        }

        $questions = Question::where('is_active', true)
            ->with('topic')
            ->get();

        if ($questions->isEmpty()) {
            return;
        }

        $users = User::where('is_admin', false)->get();

        foreach ($users as $user) {
            $baseline = rand(58, 86) / 100;
            $streakDays = rand(2, 6);
            $now = Carbon::now();

            for ($day = 0; $day < $streakDays; $day++) {
                $attemptsToday = rand(1, 3);
                for ($i = 0; $i < $attemptsToday; $i++) {
                    $question = $questions->random();
                    $answeredAt = $now->copy()
                        ->subDays($day)
                        ->subMinutes(rand(10, 720));

                    $this->seedAttempt($user->id, $question->id, $question->topic?->id, $answeredAt, $baseline);
                }
            }

            $extraAttempts = rand(60, 140);
            for ($i = 0; $i < $extraAttempts; $i++) {
                $question = $questions->random();
                $answeredAt = $now->copy()
                    ->subDays(rand(0, 35))
                    ->subMinutes(rand(0, 1440));

                $this->seedAttempt($user->id, $question->id, $question->topic?->id, $answeredAt, $baseline);
            }
        }
    }

    private function seedAttempt(int $userId, int $questionId, ?int $topicId, Carbon $answeredAt, float $baseline): void
    {
        $topicSeed = ($topicId ?? 1) % 7;
        $topicBias = ($topicSeed - 3) / 100;
        $jitter = rand(-4, 4) / 100;
        $probability = min(0.92, max(0.4, $baseline + $topicBias + $jitter));
        $isCorrect = (rand(0, 100) / 100) <= $probability;

        $timeSeconds = rand(38, 120);

        QuestionAttempt::create([
            'user_id' => $userId,
            'question_id' => $questionId,
            'is_correct' => $isCorrect,
            'time_seconds' => $timeSeconds,
            'answered_at' => $answeredAt,
            'created_at' => $answeredAt,
            'updated_at' => $answeredAt,
        ]);
    }
}
