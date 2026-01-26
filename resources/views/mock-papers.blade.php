@extends('layouts.app')

@section('title', 'REVISE MRCEM - Mock papers')

@section('content')
  <section class="page-hero page-hero--orange">
    <div class="page-hero__inner">
      <h1 class="page-hero__title">Mock papers</h1>
      <div class="page-hero__meta">
        <span class="meta-pill">Total MCQs: {{ number_format($totalMockQuestions) }}</span>
        <span class="meta-pill meta-pill--accent">Topics: {{ number_format($totalMockTopics) }}</span>
        <span class="meta-pill">Completion focus</span>
      </div>
    </div>
  </section>

  <main class="page mock-page">
    <div class="container mock-layout">
      <section class="mock-content">
        <h2 class="mock-title">Candidate instructions</h2>

        <p class="mock-intro">
          Thank you for taking the time to complete the MRCEM Clinical Problem Solving mock papers.
          They have been designed to replicate the official Clinical Problem Solving exam questions
          as closely as possible in both content & format.
        </p>

        <ul class="mock-list">
          <li>We recommend attempting these mock papers under exam conditions.</li>
          <li>
            Each mock paper includes <strong>6 items</strong> in this demo,
            and <strong>should be completed in 10 minutes</strong>.
          </li>
          <li><strong>There is no negative marking.</strong></li>
          <li>
            The answers and rationale will be presented for review at the end of the paper.
          </li>
        </ul>

        <p class="mock-note">
          The Revise MRCEM mock papers focus on <strong>clinchers, recalls</strong> and
          question subject areas known to come up <strong>frequently in the MRCEM exam</strong>,
          so be sure to review the answers and key learning summaries after completing each paper.
        </p>

        <h3 class="mock-subtitle">Select paper</h3>

        <div class="paper-selector">
          @forelse ($papers as $paper)
            <a class="paper-btn" href="{{ route('mock-papers.show', $paper) }}" title="{{ $paper->title }}">
              {{ $paper->order }}
            </a>
          @empty
            <div class="rn-empty">
              <h3>No mock papers yet</h3>
              <p>Ask an admin to add mock papers and MCQs.</p>
            </div>
          @endforelse
        </div>
      </section>

      <aside class="mock-aside">
        <div class="mastery-card">
          <div class="mastery-avatars">
            <span></span><span></span><span></span><span></span><span></span>
          </div>

          <h3 class="mastery-title">MRCEM Mastery</h3>

          <p class="mastery-text">
            With competition tougher than ever, the MRCEM Mastery Course equips you with
            high-yield learning, clinchers, recalls, and expert strategies to excel.
          </p>

          <button class="mastery-btn" type="button">
            <span class="btn-ico">↗</span>
            Learn more
          </button>

          <div class="mastery-foot">Secure your dream NHS job!</div>
        </div>
      </aside>
    </div>
  </main>
@endsection
