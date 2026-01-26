@extends('layouts.app')

@section('title', 'REVISE MRCEM • Dashboard')

@section('content')
  <main class="page">
    <div class="container">
      <section class="welcome-row">
        <div class="welcome-copy">
          <h1 class="welcome-title">Welcome back, {{ auth()->user()->name }}</h1>
          <div class="welcome-tag">{{ $examLabel }} access</div>
        </div>

        <div class="stat-row">
          <div class="stat-pill stat-pill--orange">
            <span class="stat-num">{{ $completedQuestions }}</span>
            <span class="stat-text">Completed MCQs</span>
          </div>

          <div class="stat-pill stat-pill--peach">
            <span class="stat-num">{{ $remainingQuestions }}</span>
            <span class="stat-text">Remaining MCQs</span>
          </div>

          <div class="stat-pill stat-pill--mint">
            <span class="stat-num">{{ $completionPercent }}%</span>
            <span class="stat-text">Completion</span>
          </div>
        </div>
      </section>

      <section class="cards-3">
        <a class="big-card big-card--teal" href="{{ route('question-bank') }}">
          <div class="big-card__head">Question bank</div>
          <p class="big-card__text">
            Complete the full MCQ bank. Every question is required for exam readiness.
          </p>
          <span class="big-card__arrow" aria-hidden="true">→</span>
        </a>

        <a class="big-card big-card--green" href="{{ route('mcq-session') }}">
          <div class="big-card__head">MCQ session</div>
          <p class="big-card__text">
            Work through the question bank end to end with completion tracking only.
          </p>
          <span class="big-card__arrow" aria-hidden="true">→</span>
        </a>

        <a class="big-card big-card--orange" href="{{ route('mock-papers') }}">
          <div class="big-card__head">Mock papers</div>
          <p class="big-card__text">
            Full-length MCQ papers to rehearse exam conditions and timing.
          </p>
          <span class="big-card__arrow" aria-hidden="true">→</span>
        </a>
      </section>

      <section class="cards-3 cards-3--equal">
        <article class="panel panel--pale">
          <header class="panel__header">
            <h2 class="panel__title">Completion rate</h2>
            <p class="panel__sub">Progress is tracked as done / total only.</p>
          </header>

          <div class="donut-wrap">
            <div class="donut" style="--p:{{ $completionPercent }};">
              <div class="donut__center">
                <div class="donut__value">{{ $completionPercent }}%</div>
              </div>
            </div>
          </div>
        </article>

        <article class="panel panel--pale">
          <header class="panel__header">
            <h2 class="panel__title">Completion summary</h2>
            <p class="panel__sub">All MCQs must be completed.</p>
          </header>

          <div class="completion-summary">
            <div class="completion-row">
              <span>Completed</span>
              <strong>{{ number_format($completedQuestions) }}</strong>
            </div>
            <div class="completion-row">
              <span>Total</span>
              <strong>{{ number_format($totalQuestions) }}</strong>
            </div>
            <div class="completion-row">
              <span>Remaining</span>
              <strong>{{ number_format($remainingQuestions) }}</strong>
            </div>
            <div class="progress-track" style="margin-top:12px;">
              <span class="progress-fill" style="width:{{ $completionPercent }}%"></span>
            </div>
            <div class="completion-meta">{{ $completionPercent }}% completed</div>
          </div>
        </article>

        <article class="panel panel--mint">
          <h2 class="mastery-title">Completion requirement</h2>
          <p class="mastery-text">
            The full question bank is available to all subscribers. There is no selective access — complete every MCQ to finish.
          </p>
          <div class="completion-goal">
            <span>Goal</span>
            <strong>{{ number_format($totalQuestions) }} total MCQs</strong>
          </div>
          <p class="mastery-foot">Focus on accuracy after completion.</p>
        </article>
      </section>

      <section class="topic-section">
        <h2 class="section-title">Completion by topic</h2>
        <p class="section-sub">
          Completion is tracked as done / total for each topic.
        </p>

        <div class="topic-list">
          @forelse ($topicCards as $topic)
            <article class="topic-card">
              <div class="topic-meta">
                <div class="topic-name">{{ $topic['name'] }}</div>
                <div class="topic-done">You have completed <strong>{{ $topic['attempted'] }}</strong> out of <strong>{{ $topic['total'] }}</strong> questions for this topic.</div>
              </div>

              <div class="bar-row">
                <div class="barline barline--green">
                  <span class="barfill" style="width:{{ $topic['completion_percent'] }}%"></span>
                </div>

                <div class="barvals">
                  <span>{{ $topic['completion_percent'] }}% complete</span>
                </div>
              </div>
            </article>
          @empty
            <div class="rn-empty">
              <h3>No topic data yet</h3>
              <p>Complete a few questions to populate your topic completion.</p>
            </div>
          @endforelse
        </div>
      </section>

    </div>
  </main>
@endsection
