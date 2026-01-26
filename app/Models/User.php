<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Models\Question;
use App\Models\QuestionAttempt;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function questionAttempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->whereIn('status', ['active', 'cancelled'])
            ->where('expires_at', '>=', Carbon::now())
            ->orderByDesc('expires_at')
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return (bool) $this->activeSubscription();
    }

    public function examCompletion(string $examType): array
    {
        $total = Question::query()
            ->where('is_active', true)
            ->where('exam_type', $examType)
            ->where(function ($query) {
                $query->whereHas('options')
                    ->orWhere('type', 'short_answer');
            })
            ->count();

        $completed = QuestionAttempt::query()
            ->where('user_id', $this->id)
            ->whereHas('question', function ($query) use ($examType) {
                $query->where('is_active', true)
                    ->where('exam_type', $examType)
                    ->where(function ($subquery) {
                        $subquery->whereHas('options')
                            ->orWhere('type', 'short_answer');
                    });
            })
            ->distinct('question_id')
            ->count('question_id');

        $percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'percent' => $percent,
            'is_complete' => $total > 0 && $completed >= $total,
        ];
    }

    public function hasCompletedExam(string $examType): bool
    {
        return $this->examCompletion($examType)['is_complete'];
    }
}
