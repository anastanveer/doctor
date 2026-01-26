@extends('layouts.admin')

@section('title', 'REVISE MRCEM • Question admin')
@section('page_title', $question->exists ? 'Edit question' : 'Create question')
@section('page_sub', 'Build single, multiple, ordering, matching, and short-answer formats.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.questions.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $question->exists ? route('admin.questions.update', $question) : route('admin.questions.store') }}">
        @csrf
        @if ($question->exists)
          @method('put')
        @endif

        <div class="qb-options" style="gap:14px;">
          <label class="qb-radio" style="gap:6px;">
            <span>Exam</span>
          </label>
          <select name="exam_type" id="exam-type" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
            @foreach ($examTypes as $value => $label)
              <option value="{{ $value }}" @selected(old('exam_type', $examType ?? 'primary') === $value)>
                {{ $label }}
              </option>
            @endforeach
          </select>

          <label class="qb-radio" style="gap:6px;">
            <span>Topic</span>
          </label>
          <select name="topic_id" id="topic-select" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
            <option value="">No topic</option>
            @foreach ($topics as $topic)
              <option
                value="{{ $topic->id }}"
                data-exam-type="{{ $topic->exam_type }}"
                @selected($question->topic_id === $topic->id)
              >
                {{ $topic->name }}
              </option>
            @endforeach
          </select>

          <label class="qb-radio" style="gap:6px;">
            <span>Type</span>
          </label>
          <select name="type" id="question-type" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
            @foreach ($types as $value => $label)
              <option value="{{ $value }}" @selected($question->type === $value)>{{ $label }}</option>
            @endforeach
          </select>

          <label class="qb-radio" style="gap:6px;">
            <span>Difficulty</span>
          </label>
          <input name="difficulty" type="text" value="{{ old('difficulty', $question->difficulty) }}" placeholder="Advanced / Recall / Precision" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <label class="qb-radio" style="gap:6px;">
            <span>Question stem</span>
          </label>
          <textarea name="stem" rows="4" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('stem', $question->stem) }}</textarea>

          <label class="qb-radio" style="gap:6px;">
            <span>Explanation</span>
          </label>
          <textarea name="explanation" rows="3" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('explanation', $question->explanation) }}</textarea>

          <label class="qb-radio" style="gap:6px;">
            <span>Short answer (optional)</span>
          </label>
          <input name="answer_text" type="text" value="{{ old('answer_text', $question->answer_text) }}" placeholder="Used for short answer" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />
        </div>

        <h3 style="margin:24px 0 12px;">Options (drag to reorder)</h3>
        <div class="admin-options" id="options-list">
          @php
            $optionItems = $options->count() ? $options : collect(range(1, 4))->map(fn () => (object) []);
          @endphp
          @foreach ($optionItems as $index => $option)
            <div class="admin-option" draggable="true">
              <span class="drag-handle">⋮⋮</span>
              <input type="hidden" name="options[{{ $index }}][order]" value="{{ $option->order ?? $index + 1 }}" class="option-order" />
              <input type="text" name="options[{{ $index }}][text]" value="{{ $option->text ?? '' }}" placeholder="Option text" />
              <label>
                <input type="checkbox" name="options[{{ $index }}][is_correct]" @checked($option->is_correct ?? false) />
                Correct
              </label>
              <input type="text" name="options[{{ $index }}][match_key]" value="{{ $option->match_key ?? '' }}" placeholder="Match key" />
              <input type="number" name="options[{{ $index }}][correct_order]" value="{{ $option->correct_order ?? '' }}" placeholder="Order" />
              <button type="button" class="btn-outline remove-option">Remove</button>
            </div>
          @endforeach
        </div>
        <button type="button" class="btn-outline" id="add-option" style="margin-top:12px;">Add option</button>

        <div class="qb-actions" style="margin-top:24px;">
          <button class="btn-primary-dark" type="submit">{{ $question->exists ? 'Update question' : 'Create question' }}</button>
          <label class="qb-radio" style="gap:6px;">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $question->is_active ?? true)) />
            Active
          </label>
          <label class="qb-radio" style="gap:6px;">
            <input type="checkbox" name="shuffle_options" value="1" @checked(old('shuffle_options', $question->shuffle_options ?? true)) />
            Shuffle options
          </label>
        </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    const optionsList = document.getElementById('options-list');
    const addOption = document.getElementById('add-option');
    const examSelect = document.getElementById('exam-type');
    const topicSelect = document.getElementById('topic-select');

    const syncTopics = () => {
      if (!examSelect || !topicSelect) return;
      const target = examSelect.value;
      let hasVisible = false;
      [...topicSelect.options].forEach((option) => {
        if (!option.value) {
          option.hidden = false;
          return;
        }
        const examType = option.getAttribute('data-exam-type');
        const match = !target || examType === target;
        option.hidden = !match;
        if (match) {
          hasVisible = true;
        }
      });
      const selected = topicSelect.selectedOptions[0];
      if (selected && selected.hidden) {
        topicSelect.value = '';
      }
      if (!hasVisible) {
        topicSelect.value = '';
      }
    };

    const reindexOptions = () => {
      optionsList.querySelectorAll('.admin-option').forEach((row, idx) => {
        row.querySelectorAll('input').forEach((input) => {
          if (input.name.includes('[text]')) input.name = `options[${idx}][text]`;
          if (input.name.includes('[is_correct]')) input.name = `options[${idx}][is_correct]`;
          if (input.name.includes('[match_key]')) input.name = `options[${idx}][match_key]`;
          if (input.name.includes('[correct_order]')) input.name = `options[${idx}][correct_order]`;
        });
        const orderInput = row.querySelector('.option-order');
        orderInput.name = `options[${idx}][order]`;
        orderInput.value = idx + 1;
      });
    };

    const createOptionRow = () => {
      const row = document.createElement('div');
      row.className = 'admin-option';
      row.setAttribute('draggable', 'true');
      row.innerHTML = `
        <span class="drag-handle">⋮⋮</span>
        <input type="hidden" name="options[0][order]" value="1" class="option-order" />
        <input type="text" name="options[0][text]" placeholder="Option text" />
        <label><input type="checkbox" name="options[0][is_correct]" /> Correct</label>
        <input type="text" name="options[0][match_key]" placeholder="Match key" />
        <input type="number" name="options[0][correct_order]" placeholder="Order" />
        <button type="button" class="btn-outline remove-option">Remove</button>
      `;
      optionsList.appendChild(row);
      reindexOptions();
    };

    addOption.addEventListener('click', createOptionRow);

    if (examSelect) {
      examSelect.addEventListener('change', syncTopics);
      syncTopics();
    }

    optionsList.addEventListener('click', (event) => {
      if (event.target.classList.contains('remove-option')) {
        event.target.closest('.admin-option').remove();
        reindexOptions();
      }
    });

    let dragItem = null;
    optionsList.addEventListener('dragstart', (event) => {
      dragItem = event.target.closest('.admin-option');
      dragItem.classList.add('dragging');
    });
    optionsList.addEventListener('dragend', () => {
      if (dragItem) {
        dragItem.classList.remove('dragging');
        dragItem = null;
        reindexOptions();
      }
    });
    optionsList.addEventListener('dragover', (event) => {
      event.preventDefault();
      const afterElement = [...optionsList.querySelectorAll('.admin-option:not(.dragging)')]
        .find((el) => event.clientY <= el.getBoundingClientRect().top + el.offsetHeight / 2);
      if (!afterElement) {
        optionsList.appendChild(dragItem);
      } else {
        optionsList.insertBefore(dragItem, afterElement);
      }
    });
  </script>
@endpush
