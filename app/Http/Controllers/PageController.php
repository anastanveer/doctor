<?php

namespace App\Http\Controllers;

use App\Models\QuestionAttempt;
use App\Models\Question;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PageController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();
        $examType = $this->resolveExamType($user);
        $examLabel = $this->examLabel($examType);
        $totalQuestions = Question::where('is_active', true)
            ->where('exam_type', $examType)
            ->where(function ($query) {
                $query->whereHas('options')
                    ->orWhere('type', 'short_answer');
            })
            ->count();
        $completedQuestions = QuestionAttempt::where('user_id', $user->id)
            ->whereHas('question', fn ($query) => $query->where('exam_type', $examType))
            ->distinct('question_id')
            ->count('question_id');
        $completionPercent = $totalQuestions > 0
            ? (int) round(($completedQuestions / $totalQuestions) * 100)
            : 0;
        $remainingQuestions = max(0, $totalQuestions - $completedQuestions);

        $topicCards = $this->buildTopicCards($user->id, $examType);

        return view('dashboard', [
            'examLabel' => $examLabel,
            'totalQuestions' => $totalQuestions,
            'completedQuestions' => $completedQuestions,
            'completionPercent' => $completionPercent,
            'remainingQuestions' => $remainingQuestions,
            'topicCards' => $topicCards,
        ]);
    }

    public function questionBank(): View
    {
        $examType = $this->resolveExamType(auth()->user());
        $examLabel = $this->examLabel($examType);
        $topics = Topic::where('exam_type', $examType)
            ->orderBy('name')
            ->pluck('name');
        $totalQuestions = Question::where('is_active', true)
            ->where('exam_type', $examType)
            ->where(function ($query) {
                $query->whereHas('options')
                    ->orWhere('type', 'short_answer');
            })
            ->count();
        $totalTopics = Topic::whereHas('questions', function ($query) {
            $query->where('is_active', true)
                ->where(function ($subquery) {
                    $subquery->whereHas('options')
                        ->orWhere('type', 'short_answer');
                });
        })
            ->where('exam_type', $examType)
            ->count();

        return view('question-bank', [
            'topics' => $topics,
            'examLabel' => $examLabel,
            'totalQuestions' => $totalQuestions,
            'totalTopics' => $totalTopics,
        ]);
    }

    public function revisionNotes(): View
    {
        $topics = \App\Models\RevisionTopic::withCount('notes')
            ->orderBy('name')
            ->get();

        return view('revision-notes.index', compact('topics'));
    }

    public function flashcards(): View
    {
        return view('flashcards');
    }

    public function mockPapers(): View
    {
        $papers = \App\Models\MockPaper::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('mock-papers', compact('papers'));
    }

    public function account(): View
    {
        $user = auth()->user();
        $activeSubscription = $user?->subscriptions()
            ->with('plan')
            ->whereIn('status', ['active', 'cancelled'])
            ->where('expires_at', '>=', now())
            ->orderByDesc('expires_at')
            ->first();
        $subscriptions = $user
            ? $user->subscriptions()->with('plan')->orderByDesc('started_at')->get()
            : collect();
        $plans = \App\Models\Plan::where('is_active', true)
            ->where('name', 'like', 'MRCEM %')
            ->whereIn('duration_months', [1, 3, 6])
            ->orderBy('duration_months')
            ->get()
            ->map(function (\App\Models\Plan $plan) {
                $plan->price_gbp = number_format($plan->price_cents / 100, 2);
                $plan->exam_label = $plan->examLabel();
                $plan->label = $plan->duration_months.'-month access';
                $plan->display_label = $plan->exam_label.' • '.$plan->label;

                return $plan;
            });

        return view('account', [
            'activeSubscription' => $activeSubscription,
            'subscriptions' => $subscriptions,
            'plans' => $plans,
        ]);
    }

    public function support(): View
    {
        return view('support');
    }

    public function privacy(): View
    {
        return view('privacy');
    }

    public function terms(): View
    {
        return view('terms');
    }

    public function mcqSession(): View
    {
        $examType = $this->resolveExamType(auth()->user());
        $examLabel = $this->examLabel($examType);
        $topicNames = request()->input('topics', []);
        if (!is_array($topicNames)) {
            $topicNames = [$topicNames];
        }
        $topicNames = collect($topicNames)
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $query = Question::with('topic', 'options')
            ->where('is_active', true)
            ->where('exam_type', $examType)
            ->where(function ($subquery) {
                $subquery->whereHas('options')
                    ->orWhere('type', 'short_answer');
            });

        if (!empty($topicNames)) {
            $query->whereHas('topic', function ($subquery) use ($topicNames) {
                $subquery->whereIn('name', $topicNames);
            });
        }

        $mcqQuestions = $query
            ->orderBy('id')
            ->get()
            ->map(function (Question $question) {
                $meta = is_array($question->meta) ? $question->meta : [];
                $image = isset($meta['image']) && is_string($meta['image']) ? $meta['image'] : null;
                $imageAlt = isset($meta['alt']) && is_string($meta['alt']) ? $meta['alt'] : 'Question image';
                $options = $question->options->map(function ($option, $idx) {
                    return [
                        'id' => $option->id ?? $idx,
                        'text' => $option->text,
                        'is_correct' => (bool) $option->is_correct,
                        'correct_order' => $option->correct_order,
                        'match_key' => $option->match_key,
                    ];
                })->values()->all();
                $matchOptions = $question->type === 'match'
                    ? $question->options->pluck('match_key')->filter()->unique()->values()->all()
                    : [];

                return [
                    'id' => $question->id,
                    'type' => $question->type,
                    'topic' => $question->topic?->name ?? 'General',
                    'difficulty' => $question->difficulty ?? 'General',
                    'question' => $question->stem,
                    'options' => $options,
                    'match_options' => $matchOptions,
                    'image' => $image,
                    'image_alt' => $imageAlt,
                    'insight' => $question->explanation ?? 'Review this item to strengthen recall for the exam.',
                    'explanation' => $question->explanation,
                    'answer_text' => $question->answer_text,
                    'metrics' => [],
                ];
            })
            ->values()
            ->all();

        if (empty($mcqQuestions)) {
            $mcqQuestions = [
                [
                    'id' => null,
                    'type' => 'single',
                    'topic' => 'Cardiology',
                    'difficulty' => 'Advanced',
                    'question' => 'Which valve replacement option gives the longest durability for a 54-year-old with aortic stenosis?',
                    'options' => [
                        ['id' => 1, 'text' => 'Mechanical valve + Warfarin', 'is_correct' => true],
                        ['id' => 2, 'text' => 'Bioprosthetic valve', 'is_correct' => false],
                        ['id' => 3, 'text' => 'Ross procedure', 'is_correct' => false],
                        ['id' => 4, 'text' => 'Transcatheter AVR', 'is_correct' => false],
                    ],
                    'match_options' => [],
                    'image' => null,
                    'image_alt' => 'Question image',
                    'insight' => 'Focus on durability and guideline-driven recommendations for this scenario.',
                    'explanation' => 'Mechanical valves are most durable but require lifelong anticoagulation.',
                    'answer_text' => null,
                    'metrics' => [],
                ],
            ];
        }

        $sessionTotal = count($mcqQuestions);
        $sessionTopics = collect($mcqQuestions)->pluck('topic')->unique()->count();

        return view('mcq-session', [
            'mcqQuestions' => $mcqQuestions,
            'sessionTotal' => $sessionTotal,
            'sessionTopics' => $sessionTopics,
            'examLabel' => $examLabel,
        ]);
    }

    public function freeResources(): View
    {
        return view('free-resources');
    }

    public function about(): View
    {
        return view('about');
    }

    public function reviews(): View
    {
        return view('reviews');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        $plans = \App\Models\Plan::where('is_active', true)
            ->where('name', 'like', 'MRCEM %')
            ->whereIn('duration_months', [1, 3, 6])
            ->orderBy('duration_months')
            ->get()
            ->map(function (\App\Models\Plan $plan) {
                $days = max(1, $plan->duration_months * 30);
                $perDayPence = (int) round($plan->price_cents / $days);
                $perDay = $perDayPence >= 100
                    ? 'GBP '.number_format($perDayPence / 100, 2).' per day'
                    : $perDayPence.'p per day';

                $plan->price_gbp = number_format($plan->price_cents / 100, 2);
                $plan->exam_label = $plan->examLabel();
                $plan->label = $plan->duration_months.'-month access';
                $plan->display_label = $plan->exam_label.' • '.$plan->label;
                $plan->per_day = $perDay;

                return $plan;
            });

        $plansByExam = $plans->groupBy('exam_type');
        $defaultPlanId = $plans->firstWhere('exam_type', \App\Models\Plan::EXAM_PRIMARY)?->id ?? $plans->first()?->id;
        $examTypes = [
            \App\Models\Plan::EXAM_PRIMARY => 'MRCEM Primary',
            \App\Models\Plan::EXAM_INTERMEDIATE => 'MRCEM Intermediate',
        ];

        return view('auth.register', compact('plansByExam', 'defaultPlanId', 'examTypes'));
    }

    private function buildStreaks(Collection $attempts): array
    {
        $dates = $attempts
            ->pluck('answered_at')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->unique()
            ->sort()
            ->values();

        if ($dates->isEmpty()) {
            return [0, 0];
        }

        $dateSet = $dates->flip();
        $current = 0;
        $cursor = Carbon::now()->startOfDay();
        while ($dateSet->has($cursor->toDateString())) {
            $current++;
            $cursor->subDay();
        }

        $record = 0;
        $run = 0;
        $prev = null;
        foreach ($dates as $date) {
            $currentDate = Carbon::parse($date);
            if ($prev && $currentDate->diffInDays($prev) === 1) {
                $run++;
            } else {
                $run = 1;
            }
            $record = max($record, $run);
            $prev = $currentDate;
        }

        return [$current, $record];
    }

    private function daysUntilExam(User $user): int
    {
        $subscription = $user->activeSubscription();
        if (!$subscription || !$subscription->expires_at) {
            return 0;
        }

        $days = Carbon::now()->diffInDays($subscription->expires_at, false);

        return $days > 0 ? $days : 0;
    }

    private function userAverages(): array
    {
        return QuestionAttempt::query()
            ->join('users', 'question_attempts.user_id', '=', 'users.id')
            ->where('users.is_admin', false)
            ->selectRaw('question_attempts.user_id as user_id, avg(question_attempts.is_correct) as avg_correct')
            ->groupBy('question_attempts.user_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->user_id => (float) $row->avg_correct];
            })
            ->all();
    }

    private function calculatePercentile(float $userAverage, array $averages): int
    {
        if (empty($averages)) {
            return 0;
        }

        $below = 0;
        foreach ($averages as $average) {
            if ($average <= $userAverage) {
                $below++;
            }
        }

        return (int) round(($below / count($averages)) * 100);
    }

    private function buildHistogram(array $averages): array
    {
        $bins = 19;
        $counts = array_fill(0, $bins, 0);

        foreach ($averages as $average) {
            $percent = max(0, min(100, (float) $average * 100));
            $index = (int) floor($percent / (100 / $bins));
            $index = min($bins - 1, max(0, $index));
            $counts[$index]++;
        }

        $maxCount = max($counts) ?: 1;
        $bars = [];
        foreach ($counts as $index => $count) {
            $height = 2 + (int) round(($count / $maxCount) * 21);
            $bars[] = [
                'height' => $height,
                'color' => $this->histogramColor($index, $bins),
            ];
        }

        return $bars;
    }

    private function histogramColor(int $index, int $bins): string
    {
        $ratio = $index / max(1, $bins - 1);
        if ($ratio < 0.2) {
            return 'var(--h-red)';
        }
        if ($ratio < 0.4) {
            return 'var(--h-orange)';
        }
        if ($ratio < 0.6) {
            return 'var(--h-yellow)';
        }
        if ($ratio < 0.8) {
            return 'var(--h-lime)';
        }

        return 'var(--h-green)';
    }

    private function buildTopicCards(int $userId, string $examType): array
    {
        $topics = Topic::withCount(['questions as questions_count' => function ($query) {
            $query->where('is_active', true)
                ->where(function ($subquery) {
                    $subquery->whereHas('options')
                        ->orWhere('type', 'short_answer');
                });
        }])
            ->where('exam_type', $examType)
            ->get()
            ->filter(function ($topic) {
            return $topic->questions_count > 0;
        });

        if ($topics->isEmpty()) {
            return [];
        }

        $userStats = QuestionAttempt::query()
            ->join('questions', 'question_attempts.question_id', '=', 'questions.id')
            ->selectRaw('questions.topic_id as topic_id, count(distinct question_attempts.question_id) as attempted_questions')
            ->where('question_attempts.user_id', $userId)
            ->where('questions.exam_type', $examType)
            ->groupBy('questions.topic_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [
                    $row->topic_id => [
                        'attempted_questions' => (int) $row->attempted_questions,
                    ],
                ];
            });

        return $topics
            ->map(function ($topic) use ($userStats) {
                $userData = $userStats->get($topic->id, [
                    'attempted_questions' => 0,
                ]);

                $completionPercent = $topic->questions_count > 0
                    ? (int) round(($userData['attempted_questions'] / $topic->questions_count) * 100)
                    : 0;

                return [
                    'name' => $topic->name,
                    'attempted' => $userData['attempted_questions'],
                    'total' => $topic->questions_count,
                    'completion_percent' => $completionPercent,
                ];
            })
            ->sortByDesc('attempted')
            ->take(6)
            ->values()
            ->all();
    }

    private function resolveExamType(?User $user): string
    {
        $examType = $user?->activeSubscription()?->plan?->exam_type;
        if (in_array($examType, Topic::EXAM_TYPES, true)) {
            return $examType;
        }

        return Topic::EXAM_PRIMARY;
    }

    private function examLabel(string $examType): string
    {
        return $examType === Topic::EXAM_INTERMEDIATE
            ? 'MRCEM Intermediate'
            : 'MRCEM Primary';
    }

    private function buildInsights(Collection $attempts, int $meanScore): array
    {
        $accuracyDrift = $this->accuracyDrift($attempts);
        $sparkline = $this->buildSparkline($attempts);
        $avgTime = $attempts->whereNotNull('time_seconds')->avg('time_seconds') ?? 0;
        $speedDots = $this->buildSpeedDots($attempts);
        $optimalSpeed = $this->speedBandShare($attempts, 60, 90);
        $retentionGain = $this->buildRetentionGain($attempts);

        if ($meanScore >= 78) {
            $recallStrength = 'High';
        } elseif ($meanScore >= 60) {
            $recallStrength = 'Medium';
        } else {
            $recallStrength = 'Low';
        }

        return [
            'accuracy_drift' => $accuracyDrift,
            'sparkline' => $sparkline,
            'avg_time' => (int) round($avgTime),
            'speed_dots' => $speedDots,
            'optimal_speed' => $optimalSpeed,
            'retention_gain' => $retentionGain,
            'ladder_steps' => max(1, (int) ceil($retentionGain / 20)),
            'recall_strength' => $recallStrength,
        ];
    }

    private function accuracyDrift(Collection $attempts): int
    {
        if ($attempts->count() < 10) {
            return 0;
        }

        $sorted = $attempts->sortBy('answered_at')->values();
        $split = max(1, (int) floor($sorted->count() * 0.2));
        $early = $sorted->slice(0, $sorted->count() - $split);
        $recent = $sorted->slice(-$split);

        $earlyAccuracy = $early->count() > 0
            ? $early->where('is_correct', true)->count() / $early->count()
            : 0;
        $recentAccuracy = $recent->count() > 0
            ? $recent->where('is_correct', true)->count() / $recent->count()
            : 0;

        return (int) round(($recentAccuracy - $earlyAccuracy) * 100);
    }

    private function buildSparkline(Collection $attempts): array
    {
        $start = Carbon::now()->subDays(6)->startOfDay();
        $grouped = $attempts
            ->filter(fn ($attempt) => $attempt->answered_at && $attempt->answered_at->gte($start))
            ->groupBy(fn ($attempt) => $attempt->answered_at->toDateString());

        $heights = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $dayAttempts = $grouped->get($date, collect());
            $accuracy = $dayAttempts->count() > 0
                ? $dayAttempts->where('is_correct', true)->count() / $dayAttempts->count()
                : 0.5;
            $heights[] = (int) round(8 + ($accuracy * 28));
        }

        return $heights;
    }

    private function buildSpeedDots(Collection $attempts): array
    {
        $times = $attempts
            ->whereNotNull('time_seconds')
            ->pluck('time_seconds')
            ->sort()
            ->values();

        if ($times->isEmpty()) {
            return [20, 40, 65, 82];
        }

        $quantiles = [0.2, 0.4, 0.6, 0.8];
        $values = [];
        foreach ($quantiles as $quantile) {
            $index = (int) floor(($times->count() - 1) * $quantile);
            $values[] = $times[$index] ?? $times->last();
        }

        $min = 30;
        $max = 120;
        return array_map(function ($value) use ($min, $max) {
            $percent = ($value - $min) / max(1, $max - $min) * 100;
            return (int) round(max(0, min(100, $percent)));
        }, $values);
    }

    private function speedBandShare(Collection $attempts, int $min, int $max): int
    {
        $times = $attempts->whereNotNull('time_seconds');
        if ($times->isEmpty()) {
            return 0;
        }

        $inBand = $times->filter(function ($attempt) use ($min, $max) {
            return $attempt->time_seconds >= $min && $attempt->time_seconds <= $max;
        })->count();

        return (int) round(($inBand / $times->count()) * 100);
    }

    private function buildRetentionGain(Collection $attempts): int
    {
        $groups = $attempts->filter(fn ($attempt) => $attempt->answered_at)
            ->groupBy('question_id')
            ->filter(fn ($group) => $group->count() >= 2);

        if ($groups->isEmpty()) {
            return 0;
        }

        $improved = 0;
        $total = 0;
        foreach ($groups as $group) {
            $sorted = $group->sortBy('answered_at')->values();
            $first = $sorted->first();
            $last = $sorted->last();
            if (!$first || !$last || !$first->answered_at || !$last->answered_at) {
                continue;
            }

            $hours = $first->answered_at->diffInHours($last->answered_at);
            if ($hours > 72) {
                continue;
            }

            $total++;
            if (!$first->is_correct && $last->is_correct) {
                $improved++;
            }
        }

        if ($total === 0) {
            return 0;
        }

        return (int) round(($improved / $total) * 100);
    }

    private function buildGrowth(Collection $attempts): array
    {
        $values = [];
        $start = Carbon::now()->subWeeks(7)->startOfWeek();
        $fallback = 52;

        for ($i = 0; $i < 8; $i++) {
            $weekStart = $start->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();
            $weekAttempts = $attempts->filter(function ($attempt) use ($weekStart, $weekEnd) {
                return $attempt->answered_at
                    && $attempt->answered_at->between($weekStart, $weekEnd);
            });

            if ($weekAttempts->isNotEmpty()) {
                $accuracy = $weekAttempts->where('is_correct', true)->count() / $weekAttempts->count();
                $value = (int) round($accuracy * 100);
                $fallback = $value;
            } else {
                $value = $fallback;
            }

            $values[] = max(35, min(92, $value));
        }

        $delta = $values[count($values) - 1] - $values[0];
        $paths = $this->buildSvgPaths($values, 420, 200);

        return [
            'values' => $values,
            'delta' => $delta,
            'line' => $paths['line'],
            'area' => $paths['area'],
            'dot' => $paths['dot'],
        ];
    }

    private function buildMatrix(Collection $attempts): array
    {
        $now = Carbon::now()->startOfWeek();
        $weeks = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = $now->copy()->subWeeks($i);
            $weeks[] = [
                'start' => $start,
                'end' => $start->copy()->endOfWeek(),
            ];
        }

        $times = $attempts->whereNotNull('time_seconds')->pluck('time_seconds')->sort()->values();
        if ($times->count() >= 4) {
            $q1 = $times[(int) floor(($times->count() - 1) * 0.25)];
            $q2 = $times[(int) floor(($times->count() - 1) * 0.5)];
            $q3 = $times[(int) floor(($times->count() - 1) * 0.75)];
        } else {
            [$q1, $q2, $q3] = [55, 75, 95];
        }

        $counts = array_fill(0, 4, array_fill(0, 4, 0));
        $correct = array_fill(0, 4, array_fill(0, 4, 0));

        foreach ($attempts as $attempt) {
            if (!$attempt->answered_at || $attempt->time_seconds === null) {
                continue;
            }

            $weekIndex = null;
            foreach ($weeks as $index => $week) {
                if ($attempt->answered_at->between($week['start'], $week['end'])) {
                    $weekIndex = $index;
                    break;
                }
            }

            if ($weekIndex === null) {
                continue;
            }

            $time = $attempt->time_seconds;
            $speedIndex = $time <= $q1 ? 0 : ($time <= $q2 ? 1 : ($time <= $q3 ? 2 : 3));

            $counts[$weekIndex][$speedIndex]++;
            if ($attempt->is_correct) {
                $correct[$weekIndex][$speedIndex]++;
            }
        }

        $cells = [];
        $sum = 0;
        $cellCount = 0;
        for ($row = 0; $row < 4; $row++) {
            for ($col = 0; $col < 4; $col++) {
                $attemptCount = $counts[$row][$col];
                $accuracy = $attemptCount > 0 ? $correct[$row][$col] / $attemptCount : 0.5;
                $sum += $accuracy;
                $cellCount++;
                $cells[] = $this->matrixClass($accuracy);
            }
        }

        $score = $cellCount > 0 ? (int) round(($sum / $cellCount) * 100) : 0;

        return ['cells' => $cells, 'score' => $score];
    }

    private function matrixClass(float $accuracy): string
    {
        if ($accuracy < 0.55) {
            return 'c1';
        }
        if ($accuracy < 0.65) {
            return 'c2';
        }
        if ($accuracy < 0.75) {
            return 'c3';
        }
        if ($accuracy < 0.85) {
            return 'c4';
        }

        return 'c5';
    }

    private function buildSvgPaths(array $values, int $width, int $height): array
    {
        $count = count($values);
        if ($count < 2) {
            return ['line' => '', 'area' => '', 'dot' => ['x' => 0, 'y' => 0]];
        }

        $top = 50;
        $bottom = 150;
        $stepX = $width / ($count - 1);
        $points = [];

        foreach ($values as $index => $value) {
            $x = (int) round($index * $stepX);
            $y = (int) round($bottom - (($value / 100) * ($bottom - $top)));
            $points[] = ['x' => $x, 'y' => $y];
        }

        $line = 'M'.$points[0]['x'].' '.$points[0]['y'];
        for ($i = 1; $i < count($points); $i++) {
            $line .= ' L'.$points[$i]['x'].' '.$points[$i]['y'];
        }

        $area = $line.' L'.$width.' '.$height.' L0 '.$height.' Z';
        $dot = $points[count($points) - 1];

        return ['line' => $line, 'area' => $area, 'dot' => $dot];
    }
}
