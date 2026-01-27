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
      <section class="mock-mcq" id="mock-mcq" data-paper-id="{{ $paper->id }}">
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
            <div class="mock-timer" aria-live="polite">
              <div class="mock-timer__header">
                <span class="mock-timer__title">Time trail</span>
                <span class="mock-timer__live" data-mock-live>Live</span>
              </div>
              <div class="mock-timer__stats">
                <span class="mock-timer__pill">Current: <strong data-mock-current>00:00</strong></span>
                <span class="mock-timer__pill">Total: <strong data-mock-total>00:00</strong></span>
              </div>
              <div class="mock-timer__bars" data-mock-bars></div>
            </div>
          </div>
          <div class="mock-mcq__actions">
            <a class="btn-outline" href="{{ route('mock-papers') }}">Back to papers</a>
            <button class="btn-outline" type="button" id="save-exit">Save &amp; exit</button>
            <button class="btn-primary-dark" type="button" id="finish-paper">Finish paper</button>
            <span class="mock-save-meta" data-last-saved data-saved-at="{{ $progress?->updated_at?->toIso8601String() }}"></span>
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
                $options = $question->options->values();
              @endphp
              <article class="mcq-card" data-question="{{ $index }}">
                <div class="mcq-card__top">
                  <div class="mcq-card__chips">
                    <span class="mcq-chip">Q{{ $index + 1 }}</span>
                    <span class="mcq-chip mcq-chip--muted">{{ $question->topic ?: 'General' }}</span>
                  </div>
                </div>
                <h4 class="mcq-question">{{ $question->stem }}</h4>
                @if (!empty($question->image))
                  <div class="mcq-media">
                    <img src="{{ $question->image }}" alt="{{ $question->image_alt ?? 'Question image' }}" />
                  </div>
                @endif
                <div class="mcq-options">
                  @foreach ($options as $optionIndex => $option)
                    @php $letter = chr(65 + $optionIndex); @endphp
                    <button class="mcq-option" type="button" data-option="{{ $optionIndex }}" data-correct="{{ $option->is_correct ? '1' : '0' }}">
                      <span class="mcq-option__letter">{{ $letter }}</span>
                      <span class="mcq-option__text">{{ $option->text }}</span>
                    </button>
                  @endforeach
                </div>
                <div class="mcq-card__actions">
                  <button class="btn-outline mcq-prev" type="button">Previous question</button>
                  <button class="btn-outline mcq-submit" type="button">Submit answer</button>
                  <button class="btn-outline mcq-next" type="button">Next question</button>
                  <span class="mcq-status"></span>
                </div>
                <div class="mcq-feedback"></div>
                <div class="mcq-feedback-media" hidden>
                  <img alt="" />
                </div>
                <span
                  class="mcq-explanation"
                  data-explanation="{{ e($question->explanation) }}"
                  data-explanation-image="{{ $question->explanation_image }}"
                  data-explanation-alt="{{ $question->explanation_image_alt }}"
                ></span>
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
    const mcqSection = document.getElementById('mock-mcq');
    const mcqGrid = document.getElementById('mcq-grid');
    const finishPaperBtn = document.getElementById('finish-paper');
    const saveExitBtn = document.getElementById('save-exit');
    const mcqSummary = document.getElementById('mock-summary');
    const timerCurrent = document.querySelector('[data-mock-current]');
    const timerTotal = document.querySelector('[data-mock-total]');
    const timerBarsWrap = document.querySelector('[data-mock-bars]');
    const lastSavedEl = document.querySelector('[data-last-saved]');
    const progressEndpoint = "{{ route('mock-papers.progress', $paper) }}";
    const csrfToken = "{{ csrf_token() }}";
    const serverState = @json($progress?->state ?? null);
    const serverActiveIndex = @json($progress?->active_index ?? null);
    const storageEnabled = (() => {
      try {
        const key = '__mock_storage_test__';
        localStorage.setItem(key, key);
        localStorage.removeItem(key);
        return true;
      } catch (error) {
        return false;
      }
    })();
    const paperId = mcqSection?.dataset.paperId;
    let storageKey = paperId ? `mock-paper-progress-${paperId}` : null;
    if (!storageEnabled) storageKey = null;
    let state = {};
    const cards = mcqGrid ? [...mcqGrid.querySelectorAll('.mcq-card')] : [];
    let activeIndex = 0;
    let questionStartAt = Date.now();
    let saveTimer = null;
    const timerBars = [];

    const ensureState = (index) => {
      if (!state[index]) state[index] = {};
      return state[index];
    };

    const formatDuration = (seconds) => {
      const value = Math.max(0, Math.round(seconds || 0));
      const mins = String(Math.floor(value / 60)).padStart(2, '0');
      const secs = String(value % 60).padStart(2, '0');
      return `${mins}:${secs}`;
    };

    const formatSavedAt = (value, reason) => {
      if (!value) return '';
      const date = value instanceof Date ? value : new Date(value);
      if (Number.isNaN(date.getTime())) return '';
      const label = reason === 'auto' ? 'Auto-saved' : 'Saved';
      return `${label} ${date.toLocaleString()}`;
    };

    const updateLastSaved = (value, reason) => {
      if (!lastSavedEl) return;
      const text = formatSavedAt(value, reason);
      lastSavedEl.textContent = text;
      lastSavedEl.hidden = !text;
    };

    const getQuestionTime = (index) => {
      const base = state[index]?.time_seconds || 0;
      if (index !== activeIndex) return base;
      const live = Math.max(0, Math.round((Date.now() - questionStartAt) / 1000));
      return base + live;
    };

    const updateTimerUI = () => {
      if (!cards.length) return;
      const times = cards.map((_, idx) => getQuestionTime(idx));
      const total = times.reduce((sum, value) => sum + value, 0);
      if (timerCurrent) timerCurrent.textContent = formatDuration(times[activeIndex] || 0);
      if (timerTotal) timerTotal.textContent = formatDuration(total);
      if (timerBars.length) {
        const max = Math.max(1, ...times);
        timerBars.forEach((bar, idx) => {
          const height = 8 + Math.round((times[idx] / max) * 24);
          bar.style.height = `${height}px`;
          bar.classList.toggle('is-active', idx === activeIndex);
          bar.title = `Q${idx + 1} • ${formatDuration(times[idx])}`;
        });
      }
    };

    const initTimerBars = () => {
      if (!timerBarsWrap || !cards.length) return;
      timerBarsWrap.innerHTML = '';
      cards.forEach((_, idx) => {
        const bar = document.createElement('button');
        bar.type = 'button';
        bar.className = 'mock-timer__bar';
        bar.dataset.index = idx;
        timerBarsWrap.appendChild(bar);
        timerBars.push(bar);
      });
    };

    const recordTimeSpent = (index) => {
      if (!Number.isInteger(index)) return;
      const now = Date.now();
      const elapsed = Math.max(0, Math.round((now - questionStartAt) / 1000));
      if (!elapsed) return;
      const answerState = ensureState(index);
      answerState.time_seconds = (answerState.time_seconds || 0) + elapsed;
      questionStartAt = now;
      updateTimerUI();
    };

    const showCard = (index, options = {}) => {
      if (!cards.length) return;
      if (index !== activeIndex && options.trackPrevious !== false) {
        recordTimeSpent(activeIndex);
      }
      activeIndex = index;
      cards.forEach((card, idx) => {
        card.classList.toggle('is-active', idx === index);
      });
      const activeCard = cards[index];
      if (activeCard) {
        const prevBtn = activeCard.querySelector('.mcq-prev');
        const nextBtn = activeCard.querySelector('.mcq-next');
        if (prevBtn) prevBtn.disabled = index === 0;
        if (nextBtn) nextBtn.disabled = index === cards.length - 1;
      }
      cards[index]?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      questionStartAt = Date.now();
      updateTimerUI();
    };

    const sendProgress = (reason = 'auto', immediate = false) => {
      if (!progressEndpoint) return;
      const payload = {
        state,
        active_index: activeIndex,
        reason,
      };
      fetch(progressEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
        keepalive: immediate,
      }).catch(() => {});
    };

    const persistProgress = (reason = 'auto') => {
      if (storageKey) {
        const payload = {
          state,
          activeIndex,
          updatedAt: Date.now(),
          reason,
        };
        localStorage.setItem(storageKey, JSON.stringify(payload));
      }
      updateLastSaved(new Date(), reason);
      if (progressEndpoint) {
        if (saveTimer) clearTimeout(saveTimer);
        saveTimer = setTimeout(() => sendProgress(reason), 300);
      }
    };

    const clearProgress = () => {
      if (storageKey) {
        localStorage.removeItem(storageKey);
      }
      if (progressEndpoint) {
        fetch(progressEndpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ clear: true }),
          keepalive: true,
        }).catch(() => {});
      }
    };

    const applyAnswerState = (card, answerState) => {
      if (!answerState) return;
      const status = card.querySelector('.mcq-status');
      if (answerState.selected !== undefined) {
        card.querySelectorAll('.mcq-option').forEach((btn) => {
          btn.classList.toggle('is-selected', Number(btn.dataset.option) === Number(answerState.selected));
        });
        if (status) {
          status.textContent = 'Answer selected';
          status.classList.remove('is-wrong');
        }
      }

      if (!answerState.submitted) return;

      const selectedButton = card.querySelector(`.mcq-option[data-option="${answerState.selected}"]`);
      const options = card.querySelectorAll('.mcq-option');
      options.forEach((btn) => {
        const isCorrect = btn.dataset.correct === '1';
        btn.classList.toggle('is-correct', isCorrect);
        btn.classList.toggle('is-wrong', btn === selectedButton && !isCorrect);
      });
      answerState.correct = selectedButton?.dataset.correct === '1';

      if (status) {
        status.textContent = answerState.correct ? 'Correct' : 'Incorrect';
        status.classList.toggle('is-wrong', !answerState.correct);
      }

      const feedback = card.querySelector('.mcq-feedback');
      const explanation = card.querySelector('.mcq-explanation');
      const message = explanation ? explanation.dataset.explanation : '';
      const feedbackMedia = card.querySelector('.mcq-feedback-media');
      const feedbackImg = feedbackMedia ? feedbackMedia.querySelector('img') : null;
      const explanationImage = explanation ? explanation.dataset.explanationImage : '';
      const explanationAlt = explanation ? explanation.dataset.explanationAlt : '';
      if (feedback) {
        feedback.textContent = message || 'Review this topic for clarity before moving on.';
        feedback.classList.add('is-visible');
      }
      if (feedbackMedia && feedbackImg) {
        if (explanationImage) {
          feedbackImg.src = explanationImage;
          feedbackImg.alt = explanationAlt || 'Explanation image';
          feedbackMedia.hidden = false;
        } else {
          feedbackMedia.hidden = true;
          feedbackImg.removeAttribute('src');
          feedbackImg.removeAttribute('alt');
        }
      }
    };

    const loadProgress = () => {
      if (serverState && typeof serverState === 'object') {
        state = serverState;
        if (Number.isInteger(serverActiveIndex)) {
          activeIndex = Math.min(serverActiveIndex, Math.max(cards.length - 1, 0));
        }
        return;
      }
      if (!storageKey) return;
      const raw = localStorage.getItem(storageKey);
      if (!raw) return;
      try {
        const parsed = JSON.parse(raw);
        if (!parsed || typeof parsed !== 'object') return;
        state = parsed.state && typeof parsed.state === 'object' ? parsed.state : {};
        if (Number.isInteger(parsed.activeIndex)) {
          activeIndex = Math.min(parsed.activeIndex, Math.max(cards.length - 1, 0));
        }
      } catch (error) {
        return;
      }
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
        recordTimeSpent(fromIndex);
        updateSummary();
      }
    };

    if (cards.length) {
      initTimerBars();
      loadProgress();
      cards.forEach((card) => {
        const questionIndex = Number(card.dataset.question);
        applyAnswerState(card, state[questionIndex]);
      });
      showCard(activeIndex || 0, { trackPrevious: false });
      if (Object.keys(state).length) {
        updateSummary();
      }
      updateLastSaved(lastSavedEl?.dataset.savedAt || null, 'manual');
      updateTimerUI();
      setInterval(updateTimerUI, 1000);
    }

    if (mcqGrid) {
      mcqGrid.addEventListener('click', (event) => {
        const prevButton = event.target.closest('.mcq-prev');
        if (prevButton) {
          const card = prevButton.closest('.mcq-card');
          const questionIndex = Number(card.dataset.question);
          if (questionIndex > 0) {
            showCard(questionIndex - 1);
            persistProgress('nav');
          }
          return;
        }

        const nextButton = event.target.closest('.mcq-next');
        if (nextButton) {
          const card = nextButton.closest('.mcq-card');
          const questionIndex = Number(card.dataset.question);
          goNext(questionIndex);
          persistProgress('nav');
          return;
        }

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
          persistProgress();
          return;
        }

        const submitButton = event.target.closest('.mcq-submit');
        if (submitButton) {
          const card = submitButton.closest('.mcq-card');
          const questionIndex = Number(card.dataset.question);
          const answerState = ensureState(questionIndex);

          if (answerState.selected === undefined) {
            const status = card.querySelector('.mcq-status');
            status.textContent = 'Select an option first';
            status.classList.add('is-wrong');
            return;
          }

          const selectedButton = card.querySelector(`.mcq-option[data-option="${answerState.selected}"]`);
          answerState.submitted = true;
          answerState.correct = selectedButton?.dataset.correct === '1';

          const options = card.querySelectorAll('.mcq-option');
          options.forEach((btn) => {
            const isCorrect = btn.dataset.correct === '1';
            btn.classList.toggle('is-correct', isCorrect);
            btn.classList.toggle('is-wrong', btn === selectedButton && !isCorrect);
          });

          const status = card.querySelector('.mcq-status');
          status.textContent = answerState.correct ? 'Correct' : 'Incorrect';
          status.classList.toggle('is-wrong', !answerState.correct);

          const feedback = card.querySelector('.mcq-feedback');
          const explanation = card.querySelector('.mcq-explanation');
          const message = explanation ? explanation.dataset.explanation : '';
          const feedbackMedia = card.querySelector('.mcq-feedback-media');
          const feedbackImg = feedbackMedia ? feedbackMedia.querySelector('img') : null;
          const explanationImage = explanation ? explanation.dataset.explanationImage : '';
          const explanationAlt = explanation ? explanation.dataset.explanationAlt : '';
          feedback.textContent = message || 'Review this topic for clarity before moving on.';
          feedback.classList.add('is-visible');
          if (feedbackMedia && feedbackImg) {
            if (explanationImage) {
              feedbackImg.src = explanationImage;
              feedbackImg.alt = explanationAlt || 'Explanation image';
              feedbackMedia.hidden = false;
            } else {
              feedbackMedia.hidden = true;
              feedbackImg.removeAttribute('src');
              feedbackImg.removeAttribute('alt');
            }
          }

          goNext(questionIndex);
          persistProgress();
        }
      });
    }

    timerBarsWrap?.addEventListener('click', (event) => {
      const bar = event.target.closest('.mock-timer__bar');
      if (!bar) return;
      const index = Number(bar.dataset.index);
      if (Number.isFinite(index)) {
        showCard(index);
        persistProgress('nav');
      }
    });

    finishPaperBtn?.addEventListener('click', () => {
      recordTimeSpent(activeIndex);
      updateSummary();
      clearProgress();
      updateLastSaved(null);
    });

    saveExitBtn?.addEventListener('click', () => {
      recordTimeSpent(activeIndex);
      persistProgress('manual');
      sendProgress('manual', true);
      mcqSummary.textContent = 'Progress saved. You can resume later from this question.';
      mcqSummary.hidden = false;
      window.location.href = '{{ route('mock-papers') }}';
    });

    window.addEventListener('beforeunload', () => {
      recordTimeSpent(activeIndex);
      persistProgress('auto');
      sendProgress('auto', true);
    });
  </script>
@endpush
