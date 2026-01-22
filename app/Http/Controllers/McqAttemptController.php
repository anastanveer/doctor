<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class McqAttemptController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'question_id' => ['required', 'integer', 'exists:questions,id'],
            'time_seconds' => ['nullable', 'integer', 'min:1', 'max:1800'],
        ]);

        $question = Question::with('options')->findOrFail($data['question_id']);
        $isCorrect = $this->resolveCorrectness($request, $question);

        QuestionAttempt::create([
            'user_id' => $request->user()->id,
            'question_id' => $question->id,
            'is_correct' => $isCorrect,
            'time_seconds' => $data['time_seconds'] ?? null,
            'answered_at' => now(),
        ]);

        return response()->json([
            'saved' => true,
            'correct' => $isCorrect,
        ]);
    }

    private function resolveCorrectness(Request $request, Question $question): bool
    {
        $type = $question->type ?? 'single';
        $options = $question->options->values();

        if (in_array($type, ['single', 'true_false'], true)) {
            $selectedId = $request->input('selected_option_id');
            if ($selectedId === null && $request->has('selected_index')) {
                $selected = $options->get((int) $request->input('selected_index'));
                $selectedId = $selected?->id;
            }
            if ($selectedId === null) {
                return false;
            }

            $selectedOption = $options->firstWhere('id', (int) $selectedId);
            return (bool) ($selectedOption?->is_correct ?? false);
        }

        if ($type === 'multiple') {
            $selected = $request->input('selected_option_ids', []);
            if (!is_array($selected)) {
                return false;
            }
            $selectedIds = collect($selected)->map(fn ($id) => (int) $id)->unique()->sort()->values();
            if ($selectedIds->isEmpty()) {
                return false;
            }
            $expectedIds = $options->where('is_correct', true)->pluck('id')->sort()->values();

            return $expectedIds->values()->all() === $selectedIds->values()->all();
        }

        if ($type === 'ordering') {
            $ordering = $request->input('ordering', []);
            if (!is_array($ordering) || empty($ordering)) {
                return false;
            }
            $expected = $options->sortBy('correct_order')->pluck('id')->values()->all();
            $provided = array_map('intval', $ordering);

            return $expected === $provided;
        }

        if ($type === 'match') {
            $matches = $request->input('matches', []);
            if (!is_array($matches) || empty($matches)) {
                return false;
            }
            foreach ($options as $option) {
                $selectedKey = $matches[$option->id] ?? null;
                if ($selectedKey === null) {
                    return false;
                }
                $expectedKey = (string) ($option->match_key ?? '');
                if ($expectedKey === '') {
                    return false;
                }
                if (strcasecmp(trim((string) $selectedKey), trim($expectedKey)) !== 0) {
                    return false;
                }
            }

            return true;
        }

        if ($type === 'short_answer') {
            $answer = trim((string) $request->input('short_answer', ''));
            $expected = trim((string) ($question->answer_text ?? ''));
            if ($answer === '' || $expected === '') {
                return false;
            }

            return $this->normalizeAnswer($answer) === $this->normalizeAnswer($expected);
        }

        return false;
    }

    private function normalizeAnswer(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9\s]/', '', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }
}
