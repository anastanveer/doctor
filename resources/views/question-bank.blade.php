@extends('layouts.app')

@section('title', 'REVISE MRCEM • Question bank')

@section('content')
  <section class="page-hero page-hero--teal">
    <div class="page-hero__inner">
      <h1 class="page-hero__title">{{ $examLabel }} Question bank</h1>
      <div class="page-hero__meta">
        <span class="meta-pill">Total MCQs: {{ number_format($totalQuestions) }}</span>
        <span class="meta-pill meta-pill--accent">Topics: {{ number_format($totalTopics) }}</span>
        <span class="meta-pill">eProp ready</span>
      </div>
    </div>
  </section>

  <main class="page qb-page">
    <div class="container qb-layout">
      <section class="qb-card">
        <div class="qb-head">
          <h2 class="qb-title">Full question bank session</h2>
          <p class="qb-sub">Select topics below, or start the full question bank.</p>
          <p class="qb-note">Tip: use the header search to filter topics by first letters.</p>
        </div>

        <div class="qb-check">
          <label class="select-all">
            <input id="select-all-topics" type="checkbox" />
            Select all topics
          </label>
        </div>

        <div class="topic-chips" role="list" data-topic-filter>
          @foreach ($topics as $topic)
            <button class="chip" type="button" data-topic="{{ $topic }}" aria-pressed="false">
              <span class="chip-label">{{ $topic }}</span>
              <span class="chip-status chip-status--check">&#10003;</span>
              <span class="chip-status chip-status--remove">&#10005;</span>
            </button>
          @endforeach
        </div>
        <div class="topic-empty" data-topic-empty hidden>No topics match your search.</div>

        <div class="qb-actions">
          <button class="btn-primary-dark btn-start" type="button">Start session</button>
          <button class="btn-outline btn-reset" type="button">Reset selection</button>
        </div>

        @if (!empty($searchTerm))
          <div class="qb-search-results">
            <h3>Search results for "{{ $searchTerm }}"</h3>
            @if ($searchResults->isEmpty())
              <p class="qb-search-empty">No questions or explanations match this keyword.</p>
            @else
              <ul class="qb-result-list">
                @foreach ($searchResults as $result)
                  <li class="qb-result">
                    <div class="qb-result__topic">{{ $result->topic?->name ?? 'General' }}</div>
                    <div class="qb-result__question">{{ $result->stem }}</div>
                    <div class="qb-result__excerpt">
                      {{ \Illuminate\Support\Str::limit($result->explanation ?: $result->answer_text ?: 'No explanation available yet.', 140) }}
                    </div>
                  </li>
                @endforeach
              </ul>
            @endif
            <p class="qb-search-note">Tip: click “Start session” to practise the matching results.</p>
          </div>
        @endif
      </section>

      <aside class="qb-aside">
        <article class="panel panel--mint qb-mastery">
          <div class="mastery-top">
            <div class="avatar-stack" aria-hidden="true">
              <span class="av av1"></span>
              <span class="av av2"></span>
              <span class="av av3"></span>
              <span class="av av4"></span>
              <span class="av av5"></span>
            </div>
          </div>

          <h2 class="mastery-title">MRCEM Mastery</h2>
          <p class="mastery-text">
            With competition tougher than ever, the MRCEM Mastery Course equips you with high-yield learning,
            clinchers, recalls, and expert strategies to excel.
          </p>

          <button class="btn-dark" type="button">
            <span class="btn-ico" aria-hidden="true">⤢</span>
            Learn more
          </button>

          <p class="mastery-foot">Secure your dream NHS job!</p>
        </article>
      </aside>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const startButton = document.querySelector('.btn-start');
    const resetButton = document.querySelector('.btn-reset');
    const selectAll = document.getElementById('select-all-topics');
    const chips = [...document.querySelectorAll('.chip[data-topic]')];
    const selectedTopics = new Set();
    const searchInput = document.querySelector('.header-search__input');
    const emptyState = document.querySelector('[data-topic-empty]');
    const serverFiltered = Boolean(document.querySelector('.qb-search-results'));

    const setChipState = (chip, isSelected) => {
      chip.classList.toggle('is-selected', isSelected);
      chip.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
    };

    const filterChips = (term) => {
      const value = (term || '').trim().toLowerCase();
      if (serverFiltered && value === initialQuery.toLowerCase()) {
        chips.forEach((chip) => {
          chip.hidden = false;
          chip.classList.remove('is-match');
        });
        if (emptyState) {
          emptyState.hidden = chips.length !== 0;
        }
        return;
      }
      let visibleCount = 0;
      chips.forEach((chip) => {
        const label = (chip.dataset.topic || '').toLowerCase();
        const clean = label.replace(/[^a-z0-9\s]/g, ' ');
        const words = clean.split(/\s+/).filter(Boolean);
        const stopWords = new Set(['and', 'of', 'the', 'in', 'on', 'for', 'to', 'a', 'an', 'or', 'with']);
        const initials = words
          .filter((word) => !stopWords.has(word))
          .map((word) => word[0])
          .join('');
        const matches = !value
          || label.includes(value)
          || label.startsWith(value)
          || words.some((word) => word.startsWith(value))
          || initials.startsWith(value);
        chip.hidden = !matches;
        chip.classList.toggle('is-match', Boolean(value && matches));
        if (matches) visibleCount += 1;
      });
      if (emptyState) {
        emptyState.hidden = visibleCount !== 0;
      }
    };

    const selectAllTopics = () => {
      selectedTopics.clear();
      chips.forEach((chip) => {
        const topic = chip.dataset.topic;
        if (!topic) return;
        selectedTopics.add(topic);
        setChipState(chip, true);
      });
    };

    const clearAllTopics = () => {
      selectedTopics.clear();
      chips.forEach((chip) => setChipState(chip, false));
    };

    const syncSelectAll = () => {
      if (!selectAll) return;
      if (selectedTopics.size === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
        return;
      }
      if (selectedTopics.size === chips.length) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
        return;
      }
      selectAll.checked = false;
      selectAll.indeterminate = true;
    };

    syncSelectAll();

    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        const topic = chip.dataset.topic;
        if (!topic) return;
        if (selectedTopics.has(topic)) {
          selectedTopics.delete(topic);
          setChipState(chip, false);
        } else {
          selectedTopics.add(topic);
          setChipState(chip, true);
        }
        syncSelectAll();
      });
    });

    selectAll?.addEventListener('change', () => {
      if (selectAll.checked) {
        selectAllTopics();
      } else {
        clearAllTopics();
      }
      syncSelectAll();
    });

    resetButton?.addEventListener('click', () => {
      clearAllTopics();
      syncSelectAll();
    });

    const initialQuery = new URLSearchParams(window.location.search).get('q') || '';
    if (searchInput) {
      searchInput.value = initialQuery;
      searchInput.addEventListener('input', (event) => {
        filterChips(event.target.value);
      });
    }
    filterChips(initialQuery);

    startButton?.addEventListener('click', () => {
      const url = new URL("{{ route('mcq-session') }}", window.location.origin);
      if (selectedTopics.size && selectedTopics.size < chips.length) {
        selectedTopics.forEach((topic) => url.searchParams.append('topics[]', topic));
      }
      const query = searchInput?.value?.trim();
      if (query) {
        url.searchParams.set('q', query);
      }
      window.location.href = url.toString();
    });
  </script>
@endpush
