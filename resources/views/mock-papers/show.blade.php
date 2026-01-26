@extends('layouts.app')

@section('title', 'REVISE MRCEM - Mock papers')

@section('content')
  <section class="page-hero page-hero--orange">
    <div class="page-hero__inner">
      <div class="mock-breadcrumb">
        <a href="{{ route('mock-papers') }}">Mock papers</a>
        <span>/</span>
        <span>{{ $paper->title }}</span>
      </div>
      <h1 class="page-hero__title">{{ $paper->title }}</h1>
      <p class="page-hero__sub">{{ $paper->description ?: 'Complete the MCQs and submit your answers.' }}</p>
      <div class="page-hero__meta">
        <span class="meta-pill">Paper MCQs: {{ $paper->questions->count() }}</span>
        <span class="meta-pill meta-pill--accent">Topics: {{ $paperTopics }}</span>
        <span class="meta-pill">eProp pace</span>
      </div>
    </div>
  </section>

  <main class="page mock-page">
    <div class="container mock-detail">
      <section class="mock-mcq" id="mock-mcq">
        <div class="mock-mcq__head">
          @php $demoMinutes = $paper->questions->count() * 2; @endphp
          <div>
            <h3 class="mock-mcq__title">Paper {{ $paper->order }} MCQ session</h3>
            <div class="mock-mcq__meta">
              <span class="mock-mcq__tag">CPS</span>
              <span class="mock-mcq__tag">{{ $paper->questions->count() }} questions</span>
              <span class="mock-mcq__tag">{{ $demoMinutes }} min</span>
            </div>
            <div class="mock-mcq__summary" id="mock-summary" hidden></div>
          </div>
          <div class="mock-mcq__actions">
            <a class="btn-outline" href="{{ route('mock-papers') }}">Back to papers</a>
            <button class="btn-primary-dark" type="button" id="finish-paper">Finish paper</button>
          </div>
        </div>

        @if ($paper->questions->isEmpty())
          <div class="rn-empty" style="margin-top:16px;">
            <h3>No MCQs yet</h3>
            <p>Ask an admin to add MCQs for this mock paper.</p>
          </div>
        @else
          <div class="mcq-grid" id="mcq-grid">
            @foreach ($paper->questions as $index => $question)
              @php
                $correctIndex = $question->options->search(fn ($option) => (bool) $option->is_correct);
                $correctIndex = $correctIndex === false ? -1 : $correctIndex;
              @endphp
              <article class="mcq-card" data-question="{{ $index }}" data-correct="{{ $correctIndex }}">
                <div class="mcq-card__top">
                  <div class="mcq-card__chips">
                    <span class="mcq-chip">Q{{ $index + 1 }}</span>
                    <span class="mcq-chip mcq-chip--muted">{{ $question->topic ?: 'General' }}</span>
                  </div>
                </div>
                <h4 class="mcq-question">{{ $question->stem }}</h4>
                <div class="mcq-options">
                  @foreach ($question->options as $optionIndex => $option)
                    @php $letter = chr(65 + $optionIndex); @endphp
                    <button class="mcq-option" type="button" data-option="{{ $optionIndex }}">
                      <span class="mcq-option__letter">{{ $letter }}</span>
                      <span class="mcq-option__text">{{ $option->text }}</span>
                    </button>
                  @endforeach
                </div>
                <div class="mcq-card__actions">
                  <button class="btn-outline mcq-submit" type="button">Submit answer</button>
                  <span class="mcq-status"></span>
                </div>
                <div class="mcq-feedback"></div>
                <span class="mcq-explanation" data-explanation="{{ e($question->explanation) }}"></span>
              </article>
            @endforeach
          </div>
        @endif
      </section>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const mcqGrid = document.getElementById('mcq-grid');
    const finishPaperBtn = document.getElementById('finish-paper');
    const mcqSummary = document.getElementById('mock-summary');
    const state = {};
    const cards = mcqGrid ? [...mcqGrid.querySelectorAll('.mcq-card')] : [];
    let activeIndex = 0;

    const ensureState = (index) => {
      if (!state[index]) state[index] = {};
      return state[index];
    };

    const showCard = (index) => {
      if (!cards.length) return;
      activeIndex = index;
      cards.forEach((card, idx) => {
        card.classList.toggle('is-active', idx === index);
      });
      cards[index]?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const updateSummary = () => {
      const total = cards.length;
      const attempted = Object.values(state).filter((item) => item.submitted).length;
      if (attempted < total) {
        mcqSummary.textContent = `Completion: ${attempted}/${total}. Complete all questions to finish this paper.`;
      } else {
        mcqSummary.textContent = `Paper complete: ${attempted}/${total} answered.`;
      }
      mcqSummary.hidden = false;
    };

    const goNext = (fromIndex) => {
      const nextIndex = fromIndex + 1;
      if (nextIndex < cards.length) {
        showCard(nextIndex);
      } else {
        updateSummary();
      }
    };

    if (cards.length) {
      showCard(0);
    }

    if (mcqGrid) {
      mcqGrid.addEventListener('click', (event) => {
        const optionButton = event.target.closest('.mcq-option');
        if (optionButton) {
          const card = optionButton.closest('.mcq-card');
          const questionIndex = Number(card.dataset.question);
          const answerState = ensureState(questionIndex);
          if (answerState.submitted) return;

          answerState.selected = Number(optionButton.dataset.option);
          card.querySelectorAll('.mcq-option').forEach((btn) => {
            btn.classList.toggle('is-selected', btn === optionButton);
          });
          const status = card.querySelector('.mcq-status');
          status.textContent = 'Answer selected';
          status.classList.remove('is-wrong');
          return;
        }

        const submitButton = event.target.closest('.mcq-submit');
        if (submitButton) {
          const card = submitButton.closest('.mcq-card');
          const questionIndex = Number(card.dataset.question);
          const answerState = ensureState(questionIndex);
          const correctIndex = Number(card.dataset.correct);

          if (answerState.selected === undefined) {
            const status = card.querySelector('.mcq-status');
            status.textContent = 'Select an option first';
            status.classList.add('is-wrong');
            return;
          }

          answerState.submitted = true;
          answerState.correct = answerState.selected === correctIndex;

          const options = card.querySelectorAll('.mcq-option');
          options.forEach((btn, idx) => {
            btn.classList.toggle('is-correct', idx === correctIndex);
            btn.classList.toggle('is-wrong', idx === answerState.selected && idx !== correctIndex);
          });

          const status = card.querySelector('.mcq-status');
          status.textContent = answerState.correct ? 'Correct' : 'Incorrect';
          status.classList.toggle('is-wrong', !answerState.correct);

          const feedback = card.querySelector('.mcq-feedback');
          const explanation = card.querySelector('.mcq-explanation');
          const message = explanation ? explanation.dataset.explanation : '';
          feedback.textContent = message || 'Review this topic for clarity before moving on.';
          feedback.classList.add('is-visible');

          goNext(questionIndex);
        }
      });
    }

    finishPaperBtn?.addEventListener('click', () => {
      updateSummary();
    });
  </script>
@endpush
