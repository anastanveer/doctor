@extends('layouts.app')

@section('title', 'REVISE MRCEM • MCQ session')

@section('content')
  <main class="mcq-session">
    <div class="container">
      <section class="mcq-session__top">
        <div>
          <p class="mcq-kicker">{{ $examLabel }} • Live MCQ session</p>
          <h1 class="mcq-session__title">Timed, flagged, fully responsive</h1>
        </div>
        <div class="mcq-session__meta">
          @php
            $mcqTotal = count($mcqQuestions);
            $totalSeconds = max(90, $mcqTotal * 90);
            $timeLabel = sprintf('%02d:%02d', floor($totalSeconds / 60), $totalSeconds % 60);
          @endphp
          <span class="meta-pill" data-progress-pill>Question 1 of {{ $mcqTotal }}</span>
          <span class="meta-pill meta-pill--accent" data-timer-pill>{{ $timeLabel }} / {{ $timeLabel }}</span>
          <span class="meta-pill">Session MCQs: {{ $sessionTotal }}</span>
          <span class="meta-pill">Topics: {{ $sessionTopics }}</span>
        </div>
      </section>

      <div class="mcq-session__layout">
        <section class="mcq-session__card-stack">
          <article class="mcq-card active" data-index="1" data-label="Cardiology">
            <div class="mcq-session__header">
              <div>
                <p class="mcq-session__topic">
                  <span data-topic>Topic: Cardiology</span> • <span data-difficulty>Difficulty: Advanced</span>
                </p>
                <h2 class="mcq-session__question" data-question-title></h2>
              </div>
              <button class="flag-btn" type="button" aria-pressed="false">
                <span aria-hidden="true">⚑</span>
                <span>Mark for review</span>
              </button>
            </div>
            <div class="mcq-session__media" data-question-media hidden>
              <img data-question-image alt="" />
            </div>
            <div class="mcq-session__options" data-options></div>
            <div class="mcq-answer" data-answer hidden>
              <div class="mcq-answer__status" data-answer-status></div>
              <div class="mcq-answer__correct" data-answer-correct></div>
              <div class="mcq-answer__explanation" data-answer-explanation></div>
              <div class="mcq-answer__media" data-answer-media hidden>
                <img data-answer-image alt="" />
              </div>
            </div>
            <div class="mcq-progress">
              <span data-progress-current></span>/<span data-progress-total></span>
              <div class="progress-track">
                <span class="progress-fill" data-progress-fill></span>
              </div>
            </div>
          </article>
        </section>

        <aside class="mcq-session__sidebar">
          <h3>Flagged questions</h3>
          <ul class="flagged-list">
            <li class="flagged-list__empty">Flag droplets appear here</li>
          </ul>
          <p class="sidebar-note">Flagged questions carry into your review summary for later revision.</p>
        </aside>
      </div>

      <section class="mcq-session__footer">
        <div class="mcq-session__info">
          <p>Answer reveals unlock after submission.</p>
          <p>Shortcuts: 1-4 choose an option, F flags/unflags, Space pauses the timer.</p>
        </div>
        <button class="btn-primary-dark" type="button" id="mcq-submit">Submit answer</button>
        <button class="btn-outline" type="button" id="mcq-next" hidden disabled>Next question</button>
      </section>

      <section class="mcq-complete" id="mcq-complete" hidden>
        <h2 class="mcq-complete__title">Session complete</h2>
        <p class="mcq-complete__text">
          You have completed all {{ $sessionTotal }} questions in the bank. Progress is tracked as done / total only.
        </p>
        <div class="mcq-complete__actions">
          <a class="btn-primary-dark" href="{{ route('dashboard') }}">Back to dashboard</a>
          <a class="btn-outline" href="{{ route('question-bank') }}">Question bank</a>
        </div>
      </section>

      <section class="keyboard-section">
        <div>
          <h3>Keyboard shortcuts</h3>
          <p>1-4 choose the answer, F flags the question, Space pauses/resumes the timer.</p>
        </div>
        <div class="keyboard-grid">
          <span><strong>1</strong> Option A</span>
          <span><strong>2</strong> Option B</span>
          <span><strong>3</strong> Option C</span>
          <span><strong>4</strong> Option D</span>
          <span><strong>F</strong> Flag / unflag</span>
          <span><strong>Space</strong> Pause / resume</span>
        </div>
      </section>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const mcqCard = document.querySelector('.mcq-card');
    const flaggedList = document.querySelector('.flagged-list');
    const optionContainer = mcqCard.querySelector('[data-options]');
    const questionTitle = mcqCard.querySelector('[data-question-title]');
    const topicText = mcqCard.querySelector('[data-topic]');
    const difficultyText = mcqCard.querySelector('[data-difficulty]');
    const mediaWrap = mcqCard.querySelector('[data-question-media]');
    const mediaImage = mcqCard.querySelector('[data-question-image]');
    const flagBtn = mcqCard.querySelector('.flag-btn');
    const progressCurrent = mcqCard.querySelector('[data-progress-current]');
    const progressTotal = mcqCard.querySelector('[data-progress-total]');
    const progressFill = mcqCard.querySelector('[data-progress-fill]');
    const progressPill = document.querySelector('[data-progress-pill]');
    const timerPill = document.querySelector('[data-timer-pill]');
    const submitButton = document.getElementById('mcq-submit');
    const nextButton = document.getElementById('mcq-next');
    const completePanel = document.getElementById('mcq-complete');
    const sessionLayout = document.querySelector('.mcq-session__layout');
    const sessionFooter = document.querySelector('.mcq-session__footer');
    const keyboardSection = document.querySelector('.keyboard-section');
    const answerPanel = mcqCard.querySelector('[data-answer]');
    const answerStatus = mcqCard.querySelector('[data-answer-status]');
    const answerCorrect = mcqCard.querySelector('[data-answer-correct]');
    const answerExplanation = mcqCard.querySelector('[data-answer-explanation]');
    const answerMedia = mcqCard.querySelector('[data-answer-media]');
    const answerImage = mcqCard.querySelector('[data-answer-image]');

    const questions = @json($mcqQuestions);
    let currentIndex = 0;
    const flagged = new Set();
    let remainingSeconds = questions.length * 90;
    let timerId = null;
    let isPaused = false;
    let hasSubmitted = false;
    let questionStart = Date.now();

    const formatTime = (seconds) => {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    };

    const updateTimer = () => {
      if (!timerPill) return;
      const totalSeconds = questions.length * 90;
      timerPill.textContent = `${formatTime(remainingSeconds)} / ${formatTime(totalSeconds)}`;
    };

    const tickTimer = () => {
      remainingSeconds = Math.max(0, remainingSeconds - 1);
      updateTimer();
      if (remainingSeconds === 0 && timerId) {
        clearInterval(timerId);
        timerId = null;
      }
    };

    const startTimer = () => {
      if (!timerPill || timerId) return;
      timerId = setInterval(tickTimer, 1000);
    };

    const stopTimer = () => {
      if (!timerId) return;
      clearInterval(timerId);
      timerId = null;
    };

    const updateFlaggedList = () => {
      if (!flaggedList) return;
      flaggedList.innerHTML = '';
      if (!flagged.size) {
        const placeholder = document.createElement('li');
        placeholder.className = 'flagged-list__empty';
        placeholder.textContent = 'Flag droplets appear here';
        flaggedList.appendChild(placeholder);
        return;
      }
      flagged.forEach((idx) => {
        const entry = document.createElement('li');
        entry.textContent = `Q${idx + 1} • ${questions[idx].topic}`;
        flaggedList.appendChild(entry);
      });
    };

    const shuffleArray = (list) => {
      const copy = [...list];
      for (let i = copy.length - 1; i > 0; i -= 1) {
        const j = Math.floor(Math.random() * (i + 1));
        [copy[i], copy[j]] = [copy[j], copy[i]];
      }
      return copy;
    };

    const setupOrderDrag = (list) => {
      let dragItem = null;
      list.addEventListener('dragstart', (event) => {
        const item = event.target.closest('.order-item');
        if (!item) return;
        dragItem = item;
        dragItem.classList.add('is-dragging');
      });
      list.addEventListener('dragend', () => {
        if (!dragItem) return;
        dragItem.classList.remove('is-dragging');
        dragItem = null;
      });
      list.addEventListener('dragover', (event) => {
        event.preventDefault();
        if (!dragItem) return;
        const afterElement = [...list.querySelectorAll('.order-item:not(.is-dragging)')]
          .find((el) => event.clientY <= el.getBoundingClientRect().top + el.offsetHeight / 2);
        if (!afterElement) {
          list.appendChild(dragItem);
        } else {
          list.insertBefore(dragItem, afterElement);
        }
      });
    };

    const renderMedia = (item) => {
      if (!mediaWrap || !mediaImage) return;
      if (item.image) {
        mediaImage.src = item.image;
        mediaImage.alt = item.image_alt || 'Question image';
        mediaWrap.hidden = false;
      } else {
        mediaWrap.hidden = true;
        mediaImage.removeAttribute('src');
      }
    };

    const renderOptions = (item) => {
      optionContainer.innerHTML = '';
      optionContainer.classList.remove('is-multiple', 'is-ordering', 'is-match', 'is-short-answer');
      const type = item.type || 'single';

      if (type === 'ordering') {
        optionContainer.classList.add('is-ordering');
        const list = document.createElement('ul');
        list.className = 'order-list';
        shuffleArray(item.options || []).forEach((option, idx) => {
          const row = document.createElement('li');
          row.className = 'order-item';
          row.draggable = true;
          row.dataset.optionId = option.id ?? idx;
          row.innerHTML = `<span class="order-handle">⋮⋮</span><span>${option.text}</span>`;
          list.appendChild(row);
        });
        optionContainer.appendChild(list);
        setupOrderDrag(list);
        return;
      }

      if (type === 'match') {
        optionContainer.classList.add('is-match');
        const wrap = document.createElement('div');
        wrap.className = 'match-grid';
        const matchOptions = shuffleArray(item.match_options || []);
        (item.options || []).forEach((option, idx) => {
          const row = document.createElement('div');
          row.className = 'match-row';
          const select = document.createElement('select');
          select.className = 'match-select';
          select.dataset.optionId = option.id ?? idx;
          const placeholder = document.createElement('option');
          placeholder.value = '';
          placeholder.textContent = 'Select match';
          select.appendChild(placeholder);
          matchOptions.forEach((choice) => {
            const opt = document.createElement('option');
            opt.value = choice;
            opt.textContent = choice;
            select.appendChild(opt);
          });
          row.innerHTML = `<span class="match-label">${option.text}</span>`;
          row.appendChild(select);
          wrap.appendChild(row);
        });
        optionContainer.appendChild(wrap);
        return;
      }

      if (type === 'short_answer') {
        optionContainer.classList.add('is-short-answer');
        const inputWrap = document.createElement('div');
        inputWrap.className = 'short-answer';
        inputWrap.innerHTML = `
          <label class="short-label" for="short-answer">Short answer</label>
          <input id="short-answer" class="short-input" type="text" placeholder="Type your answer" />
        `;
        optionContainer.appendChild(inputWrap);
        return;
      }

      if (type === 'multiple') {
        optionContainer.classList.add('is-multiple');
      }

      (item.options || []).forEach((option, idx) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.dataset.optionId = option.id ?? idx;
        button.textContent = option.text;
        optionContainer.appendChild(button);
      });
    };

    const renderQuestion = (index) => {
      const item = questions[index];
      questionStart = Date.now();
      mcqCard.dataset.index = index + 1;
      mcqCard.dataset.label = item.topic;
      questionTitle.textContent = item.question;
      topicText.textContent = `Topic: ${item.topic}`;
      difficultyText.textContent = `Difficulty: ${item.difficulty}`;
      renderMedia(item);
      renderOptions(item);
      progressCurrent.textContent = index + 1;
      progressTotal.textContent = questions.length;
      progressFill.style.width = `${((index + 1) / questions.length) * 100}%`;
      if (progressPill) {
        progressPill.textContent = `Question ${index + 1} of ${questions.length}`;
      }
      flagBtn.setAttribute('aria-pressed', flagged.has(index) ? 'true' : 'false');
      flagBtn.classList.toggle('is-flagged', flagged.has(index));
      resetAnswerState();
    };

    const completeSession = () => {
      stopTimer();
      if (progressPill) {
        progressPill.textContent = 'Completed';
      }
      if (timerPill) {
        timerPill.textContent = `${formatTime(remainingSeconds)} / ${formatTime(questions.length * 90)}`;
      }
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Completed';
      }
      if (nextButton) {
        nextButton.disabled = true;
        nextButton.hidden = true;
      }
      if (sessionLayout) sessionLayout.hidden = true;
      if (sessionFooter) sessionFooter.hidden = true;
      if (keyboardSection) keyboardSection.hidden = true;
      if (completePanel) completePanel.hidden = false;
    };

    const moveToNextQuestion = () => {
      const nextIndex = currentIndex + 1;
      if (nextIndex < questions.length) {
        currentIndex = nextIndex;
        renderQuestion(currentIndex);
        updateFlaggedList();
      } else {
        completeSession();
      }
    };

    const handleOptionSelection = (button) => {
      if (hasSubmitted) {
        return;
      }
      if (optionContainer.classList.contains('is-multiple')) {
        button.classList.toggle('is-active');
        return;
      }
      const options = optionContainer.querySelectorAll('button');
      options.forEach((option) => option.classList.remove('is-active'));
      button.classList.add('is-active');
    };

    optionContainer.addEventListener('click', (event) => {
      const button = event.target.closest('button[data-option-id]');
      if (!button) return;
      handleOptionSelection(button);
    });

    flagBtn.addEventListener('click', () => {
      const pressed = flagBtn.getAttribute('aria-pressed') === 'true';
      flagBtn.setAttribute('aria-pressed', (!pressed).toString());
      flagBtn.classList.toggle('is-flagged', !pressed);
      if (pressed) {
        flagged.delete(currentIndex);
      } else {
        flagged.add(currentIndex);
      }
      updateFlaggedList();
    });

    document.addEventListener('keydown', (event) => {
      if (/^[1-4]$/.test(event.key)) {
        const options = optionContainer.querySelectorAll('button[data-option-id]');
        const target = options[Number(event.key) - 1];
        if (target) {
          handleOptionSelection(target);
        }
      }
      if (event.key.toLowerCase() === 'f') {
        flagBtn.click();
      }
      if (event.code === 'Space') {
        event.preventDefault();
        isPaused = !isPaused;
        if (isPaused) {
          stopTimer();
        } else {
          startTimer();
        }
      }
    });

    renderQuestion(currentIndex);
    updateFlaggedList();
    updateTimer();
    startTimer();

    submitButton?.addEventListener('click', () => {
      if (hasSubmitted) {
        return;
      }
      const item = questions[currentIndex];
      if (!item) return;
      const type = item.type || 'single';
      const payload = {
        question_id: item.id,
        time_seconds: Math.max(1, Math.round((Date.now() - questionStart) / 1000)),
      };

      if (type === 'multiple') {
        const selected = [...optionContainer.querySelectorAll('button.is-active')]
          .map((button) => Number(button.dataset.optionId))
          .filter((id) => Number.isFinite(id));
        if (!selected.length) {
          alert('Select at least one option before submitting.');
          return;
        }
        payload.selected_option_ids = selected;
      } else if (type === 'ordering') {
        const ordering = [...optionContainer.querySelectorAll('.order-item')]
          .map((row) => Number(row.dataset.optionId))
          .filter((id) => Number.isFinite(id));
        if (!ordering.length) {
          alert('Reorder the steps before submitting.');
          return;
        }
        payload.ordering = ordering;
      } else if (type === 'match') {
        const selects = [...optionContainer.querySelectorAll('select[data-option-id]')];
        const matches = {};
        let missing = false;
        selects.forEach((select) => {
          if (!select.value) {
            missing = true;
          }
          matches[select.dataset.optionId] = select.value || '';
        });
        if (missing) {
          alert('Match every item before submitting.');
          return;
        }
        payload.matches = matches;
      } else if (type === 'short_answer') {
        const input = optionContainer.querySelector('.short-input');
        const value = input ? input.value.trim() : '';
        if (!value) {
          alert('Type your answer before submitting.');
          return;
        }
        payload.short_answer = value;
      } else {
        const selectedButton = optionContainer.querySelector('button.is-active');
        if (!selectedButton) {
          alert('Please select an option before submitting.');
          return;
        }
        payload.selected_option_id = Number(selectedButton.dataset.optionId);
      }

      if (item.id) {
        fetch("{{ route('mcq-attempts.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
          },
          body: JSON.stringify(payload),
        }).catch(() => {});
      }
      revealAnswer(item, payload);
    });

    nextButton?.addEventListener('click', () => {
      if (!hasSubmitted) {
        return;
      }
      moveToNextQuestion();
    });

    function resetAnswerState() {
      hasSubmitted = false;
      optionContainer.classList.remove('is-locked');
      optionContainer.querySelectorAll('button').forEach((button) => {
        button.classList.remove('is-correct', 'is-wrong');
        button.disabled = false;
      });
      optionContainer.querySelectorAll('select').forEach((select) => {
        select.disabled = false;
      });
      optionContainer.querySelectorAll('.order-item').forEach((item) => {
        item.draggable = true;
      });
      const shortInput = optionContainer.querySelector('.short-input');
      if (shortInput) {
        shortInput.disabled = false;
      }
      if (answerPanel) {
        answerPanel.hidden = true;
      }
      if (answerStatus) {
        answerStatus.textContent = '';
        answerStatus.classList.remove('is-correct', 'is-wrong', 'is-pending');
      }
      if (answerCorrect) {
        answerCorrect.innerHTML = '';
      }
      if (answerExplanation) {
        answerExplanation.textContent = '';
      }
      if (answerMedia) {
        answerMedia.hidden = true;
      }
      if (answerImage) {
        answerImage.removeAttribute('src');
        answerImage.removeAttribute('alt');
      }
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.textContent = 'Submit answer';
      }
      if (nextButton) {
        nextButton.disabled = true;
        nextButton.hidden = true;
      }
    }

    function setAnswerStatus(label, tone) {
      if (!answerStatus) {
        return;
      }
      answerStatus.textContent = label;
      answerStatus.classList.remove('is-correct', 'is-wrong', 'is-pending');
      if (tone) {
        answerStatus.classList.add(tone);
      }
    }

    function addAnswerList(title, items, ordered) {
      if (!answerCorrect) {
        return;
      }
      const heading = document.createElement('div');
      heading.className = 'mcq-answer__label';
      heading.textContent = title;
      answerCorrect.appendChild(heading);

      if (!items.length) {
        const empty = document.createElement('div');
        empty.className = 'mcq-answer__empty';
        empty.textContent = 'Answer key not provided yet.';
        answerCorrect.appendChild(empty);
        return;
      }

      const list = document.createElement(ordered ? 'ol' : 'ul');
      list.className = 'mcq-answer__list';
      items.forEach((text) => {
        const li = document.createElement('li');
        li.textContent = text;
        list.appendChild(li);
      });
      answerCorrect.appendChild(list);
    }

    function revealAnswer(item, payload) {
      hasSubmitted = true;
      optionContainer.classList.add('is-locked');
      const type = item.type || 'single';
      const options = Array.isArray(item.options) ? item.options : [];
      const correctOptions = options.filter((option) => option.is_correct);
      const correctIds = correctOptions.map((option) => option.id);
      let isCorrect = null;

      if (answerCorrect) {
        answerCorrect.innerHTML = '';
      }

      if (type === 'multiple') {
        const selected = payload.selected_option_ids || [];
        isCorrect = correctIds.length > 0
          && correctIds.length === selected.length
          && correctIds.every((id) => selected.includes(id));
        optionContainer.querySelectorAll('button').forEach((button) => {
          const id = Number(button.dataset.optionId);
          if (correctIds.includes(id)) {
            button.classList.add('is-correct');
          } else if (selected.includes(id)) {
            button.classList.add('is-wrong');
          }
          button.disabled = true;
        });
        addAnswerList('Correct answers', correctOptions.map((option) => option.text), false);
      } else if (type === 'ordering') {
        const userOrdering = payload.ordering || [];
        const ordered = options.filter((option) => Number.isFinite(option.correct_order))
          .sort((a, b) => a.correct_order - b.correct_order);
        const correctOrdering = ordered.length ? ordered : options;
        const correctOrderIds = correctOrdering.map((option, index) => option.id ?? index);
        isCorrect = correctOrderIds.length > 0
          && correctOrderIds.length === userOrdering.length
          && correctOrderIds.every((id, index) => userOrdering[index] === id);
        optionContainer.querySelectorAll('.order-item').forEach((row) => {
          row.draggable = false;
        });
        addAnswerList('Correct order', correctOrdering.map((option) => option.text), true);
      } else if (type === 'match') {
        const userMatches = payload.matches || {};
        const correctMatches = {};
        options.forEach((option) => {
          correctMatches[option.id] = option.match_key;
        });
        const matchIds = Object.keys(correctMatches);
        isCorrect = matchIds.length > 0
          && matchIds.every((id) => String(userMatches[id] || '') === String(correctMatches[id] || ''));
        optionContainer.querySelectorAll('select').forEach((select) => {
          select.disabled = true;
        });
        const matchLines = options.map((option) => `${option.text} → ${option.match_key || '—'}`);
        addAnswerList('Correct matches', matchLines, false);
      } else if (type === 'short_answer') {
        const answerText = item.answer_text || '';
        const userAnswer = (payload.short_answer || '').trim().toLowerCase();
        if (answerText) {
          isCorrect = userAnswer === answerText.trim().toLowerCase();
        }
        const shortInput = optionContainer.querySelector('.short-input');
        if (shortInput) {
          shortInput.disabled = true;
        }
        addAnswerList('Suggested answer', answerText ? [answerText] : [], false);
      } else {
        const selectedId = payload.selected_option_id;
        isCorrect = correctIds.length > 0 && correctIds.includes(selectedId);
        optionContainer.querySelectorAll('button').forEach((button) => {
          const id = Number(button.dataset.optionId);
          if (correctIds.includes(id)) {
            button.classList.add('is-correct');
          } else if (id === selectedId) {
            button.classList.add('is-wrong');
          }
          button.disabled = true;
        });
        addAnswerList('Correct answer', correctOptions.map((option) => option.text), false);
      }

      if (answerExplanation) {
        const explanation = item.explanation || item.insight || '';
        answerExplanation.textContent = explanation;
      }
      if (answerMedia && answerImage) {
        if (item.explanation_image) {
          answerImage.src = item.explanation_image;
          answerImage.alt = item.explanation_image_alt || 'Explanation image';
          answerMedia.hidden = false;
        } else {
          answerMedia.hidden = true;
          answerImage.removeAttribute('src');
          answerImage.removeAttribute('alt');
        }
      }

      if (answerPanel) {
        answerPanel.hidden = false;
      }

      if (isCorrect === true) {
        setAnswerStatus('Correct', 'is-correct');
      } else if (isCorrect === false) {
        setAnswerStatus('Incorrect', 'is-wrong');
      } else {
        setAnswerStatus('Answer submitted', 'is-pending');
      }

      if (submitButton) {
        submitButton.disabled = true;
      }
      if (nextButton) {
        nextButton.disabled = false;
        nextButton.hidden = false;
      }
    }
  </script>
@endpush
