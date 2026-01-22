@extends('layouts.admin')

@section('title', 'REVISE MSRA • MCQ results')
@section('page_title', 'MCQ results')
@section('page_sub', 'Track real user performance and session momentum.')

@section('content')
  <div class="admin-panel">
    <section class="admin-kpis">
      <div class="admin-kpi">
        <p>Total attempts</p>
        <h3>{{ number_format($totalAttempts) }}</h3>
        <span class="kpi-foot">Live question submissions</span>
      </div>
      <div class="admin-kpi">
        <p>Accuracy</p>
        <h3>{{ $accuracy }}%</h3>
        <span class="kpi-foot">Across all users</span>
      </div>
      <div class="admin-kpi">
        <p>Avg. time</p>
        <h3>{{ $avgTime }}s</h3>
        <span class="kpi-foot">Per question</span>
      </div>
      <div class="admin-kpi">
        <p>Active learners</p>
        <h3>{{ number_format($activeUsers) }}</h3>
        <span class="kpi-foot">Last 7 days</span>
      </div>
    </section>

    <section class="admin-grid">
      <div class="admin-card">
        <div class="admin-card__head">
          <h3>User performance</h3>
        </div>
        <div class="admin-table">
          <div class="admin-row admin-row--head">
            <span>User</span>
            <span>Attempts</span>
            <span>Accuracy</span>
            <span>Avg. time</span>
            <span>Last attempt</span>
          </div>
          @forelse ($userStats as $stat)
            <div class="admin-row">
              <span>{{ $stat->user?->name ?? 'Unknown' }}</span>
              <span>{{ $stat->attempts }}</span>
              <span>{{ $stat->accuracy ? (int) round($stat->accuracy * 100) : 0 }}%</span>
              <span>{{ $stat->avg_time ? (int) round($stat->avg_time) : 0 }}s</span>
              <span>{{ $stat->last_attempt ? \Illuminate\Support\Carbon::parse($stat->last_attempt)->format('d M Y') : '—' }}</span>
            </div>
          @empty
            <div class="admin-row">
              <span>No user attempts yet.</span>
              <span></span><span></span><span></span><span></span>
            </div>
          @endforelse
        </div>
      </div>

      <div class="admin-card">
        <div class="admin-card__head">
          <h3>Recent attempts</h3>
        </div>
        <div class="admin-table">
          <div class="admin-row admin-row--head">
            <span>User</span>
            <span>Topic</span>
            <span>Question</span>
            <span>Result</span>
            <span>Time</span>
          </div>
          @forelse ($recentAttempts as $attempt)
            <div class="admin-row">
              <span>{{ $attempt->user?->name ?? 'Unknown' }}</span>
              <span>{{ $attempt->question?->topic?->name ?? 'General' }}</span>
              <span>{{ \Illuminate\Support\Str::limit($attempt->question?->stem ?? '—', 48) }}</span>
              <span class="status-pill {{ $attempt->is_correct ? '' : 'status-pill--muted' }}">
                {{ $attempt->is_correct ? 'Correct' : 'Incorrect' }}
              </span>
              <span>{{ $attempt->time_seconds ?? 0 }}s</span>
            </div>
          @empty
            <div class="admin-row">
              <span>No attempts yet.</span>
              <span></span><span></span><span></span><span></span>
            </div>
          @endforelse
        </div>
        <div style="margin-top:16px;">
          {{ $recentAttempts->links('pagination.admin') }}
        </div>
      </div>
    </section>
  </div>
@endsection
