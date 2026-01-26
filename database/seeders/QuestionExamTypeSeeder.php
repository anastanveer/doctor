<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class QuestionExamTypeSeeder extends Seeder
{
    public function run(): void
    {
        $topicTypes = Topic::pluck('exam_type', 'id');

        Question::query()
            ->whereNotNull('topic_id')
            ->orderBy('id')
            ->chunkById(200, function ($questions) use ($topicTypes) {
                foreach ($questions as $question) {
                    $topicExam = $topicTypes[$question->topic_id] ?? null;
                    if ($topicExam && $question->exam_type !== $topicExam) {
                        $question->exam_type = $topicExam;
                        $question->save();
                    }
                }
            });
    }
}
