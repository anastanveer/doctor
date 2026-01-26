@extends('layouts.admin')

@section('title', 'REVISE MRCEM • Admin dashboard')
@section('page_title', 'eProp control center')
@section('page_sub', 'Track performance, subscriptions, and cohorts in real time.')

@section('content')
  @php
    $weeklySignups = $signups->sum('value');
    $peakSignup = $signups->max('value') ?? 0;
    $latestAccuracy = ($accuracy->last()['value'] ?? 0);
  @endphp
  <div class="admin-panel">
    <section class="admin-hero">
      <div class="admin-hero__main">
        <span class="admin-hero__tag">Live operations</span>
        <h2 class="admin-hero__title">eProp control center</h2>
        <p class="admin-hero__text">
          {{ number_format($activeSubscriptions) }} active subscriptions, {{ number_format($expiringSoon) }} expiring soon.
          Weekly signups: {{ number_format($weeklySignups) }}.
        </p>
        <div class="admin-hero__stats">
          <div class="admin-hero__stat">
            <span>Weekly signups</span>
            <strong>{{ number_format($weeklySignups) }}</strong>
          </div>
          <div class="admin-hero__stat">
            <span>Peak day</span>
            <strong>{{ number_format($peakSignup) }}</strong>
          </div>
          <div class="admin-hero__stat">
            <span>Latest accuracy</span>
            <strong>{{ $latestAccuracy }}%</strong>
          </div>
        </div>
      </div>
      <div class="admin-hero__panel">
        <div>
          <h3 class="admin-hero__panel-title">Quick actions</h3>
          <p class="admin-hero__panel-text">Create content and jump into performance data.</p>
        </div>
        <div class="admin-hero__actions">
          <a class="admin-hero__btn" href="{{ route('admin.questions.create') }}">Add MCQ <span aria-hidden="true">→</span></a>
          <a class="admin-hero__btn admin-hero__btn--ghost" href="{{ route('admin.mock-papers.create') }}">New mock paper <span aria-hidden="true">→</span></a>
          <a class="admin-hero__btn admin-hero__btn--ghost" href="{{ route('admin.attempts.index') }}">MCQ results <span aria-hidden="true">→</span></a>
        </div>
        <div class="admin-hero__panel-foot">
          <span>Revenue</span>
          <strong>£{{ number_format($revenue / 100, 2) }}</strong>
        </div>
      </div>
    </section>

    <section class="admin-kpis">
      <div class="admin-kpi">
        <span class="admin-kpi__icon" aria-hidden="true">◆</span>
        <p>Total users</p>
        <h3>{{ number_format($usersCount) }}</h3>
        <span class="kpi-foot">{{ $weeklyGrowth >= 0 ? '+' : '' }}{{ $weeklyGrowth }}% this week</span>
      </div>
      <div class="admin-kpi">
        <span class="admin-kpi__icon" aria-hidden="true">◎</span>
        <p>Active subscriptions</p>
        <h3>{{ number_format($activeSubscriptions) }}</h3>
        <span class="kpi-foot">Renewals trending up</span>
      </div>
      <div class="admin-kpi">
        <span class="admin-kpi__icon" aria-hidden="true">△</span>
        <p>Expiring in 7 days</p>
        <h3>{{ number_format($expiringSoon) }}</h3>
        <span class="kpi-foot">Send reminder emails</span>
      </div>
      <div class="admin-kpi">
        <span class="admin-kpi__icon" aria-hidden="true">£</span>
        <p>Monthly revenue</p>
        <h3>£{{ number_format($revenue / 100, 2) }}</h3>
        <span class="kpi-foot">Avg. plan retention 76%</span>
      </div>
    </section>

    <section class="admin-charts admin-charts--lift">
      <div class="admin-card admin-card--graph">
        <div class="admin-card__head">
          <h3>Weekly signups</h3>
          <span class="admin-badge">Live</span>
        </div>
        <div class="spark-bars">
          @foreach ($signups as $point)
            <span style="--h: {{ $point['value'] }};"><i>{{ $point['label'] }}</i></span>
          @endforeach
        </div>
      </div>

      <div class="admin-card admin-card--graph admin-card--sun">
        <div class="admin-card__head">
          <h3>Accuracy trend</h3>
          <span class="admin-badge admin-badge--mint">eProp</span>
        </div>
        <div class="line-track">
          @foreach ($accuracy as $point)
            <span style="--x: {{ $loop->index }}; --y: {{ $point['value'] }};"></span>
          @endforeach
        </div>
        <div class="line-legend">
          @foreach ($accuracy as $point)
            <span>{{ $point['label'] }}</span>
          @endforeach
        </div>
      </div>
    </section>

    <section class="admin-grid admin-grid--focus">
      <div class="admin-card admin-card--table">
        <div class="admin-card__head">
          <h3>Latest users</h3>
          <a href="{{ route('admin.users.index') }}">View all</a>
        </div>
        <div class="admin-table">
          <div class="admin-row admin-row--head">
            <span>Name</span>
            <span>Email</span>
            <span>Status</span>
          </div>
          @foreach ($recentUsers as $user)
            <div class="admin-row">
              <span>{{ $user->name }}</span>
              <span>{{ $user->email }}</span>
              <span class="status-pill">Active</span>
            </div>
          @endforeach
        </div>
      </div>

      <div class="admin-card admin-card--table">
        <div class="admin-card__head">
          <h3>Plans snapshot</h3>
          <a href="{{ route('admin.subscriptions.index') }}">Manage</a>
        </div>
        <div class="admin-list">
          @foreach ($plans as $plan)
            <div>
              <strong>{{ $plan->name }}</strong>
              <span>{{ $plan->subscriptions_count }} active</span>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="admin-grid">
      <div class="admin-card">
        <div class="admin-card__head">
          <h3>Coupons</h3>
          <a href="{{ route('admin.coupons.index') }}">Create</a>
        </div>
        <div class="admin-list">
          @forelse ($coupons as $coupon)
            <div>
              <strong>{{ $coupon->code }}</strong>
              <span>{{ strtoupper($coupon->type) }} {{ $coupon->value }}</span>
            </div>
          @empty
            <p>No coupons yet.</p>
          @endforelse
        </div>
      </div>

      <div class="admin-card admin-card--highlight">
        <h3>Action center</h3>
        <p>Broadcast announcements, schedule email campaigns, and monitor payment retries.</p>
        <button class="btn-primary-dark" type="button">Launch broadcast</button>
      </div>
    </section>
  </div>
@endsection
