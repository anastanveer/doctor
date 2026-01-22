@extends('layouts.admin')

@section('title', 'REVISE MSRA - Mock MCQs')
@section('page_title', $question->exists ? 'Edit mock MCQ' : 'Create mock MCQ')
@section('page_sub', 'Each MCQ appears inside the selected mock paper.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.mock-questions.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $question->exists ? route('admin.mock-questions.update', $question) : route('admin.mock-questions.store') }}">
      @csrf
      @if ($question->exists)
        @method('put')
      @endif

      <div class="qb-options" style="gap:14px;">
        <label class="qb-radio" style="gap:6px;">
          <span>Mock paper</span>
        </label>
        <select name="mock_paper_id" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
          @foreach ($papers as $paper)
            <option value="{{ $paper->id }}" @selected((int) old('mock_paper_id', $selectedPaper ?? $question->mock_paper_id) === $paper->id)>{{ $paper->title }}</option>
          @endforeach
        </select>

        <label class="qb-radio" style="gap:6px;">
          <span>Order</span>
        </label>
        <input name="order" type="number" min="1" value="{{ old('order', $question->order ?? 1) }}" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Topic (optional)</span>
        </label>
        <input name="topic" type="text" value="{{ old('topic', $question->topic) }}" placeholder="Cardiology" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Question stem</span>
        </label>
        <textarea name="stem" rows="4" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('stem', $question->stem) }}</textarea>

        <label class="qb-radio" style="gap:6px;">
          <span>Explanation (optional)</span>
        </label>
        <textarea name="explanation" rows="3" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('explanation', $question->explanation) }}</textarea>
      </div>

      <h3 style="margin:24px 0 12px;">Options</h3>
      <div class="admin-options" id="options-list">
        @php
          $optionItems = $options->count() ? $options : collect(range(1, 4))->map(fn () => (object) []);
          $correctValue = old('correct_option', $correctIndex ?? 0);
        @endphp
        @foreach ($optionItems as $index => $option)
          <div class="admin-option" draggable="true">
            <span class="drag-handle">::</span>
            <input type="hidden" name="options[{{ $index }}][order]" value="{{ $option->order ?? $index + 1 }}" class="option-order" />
            <input type="text" name="options[{{ $index }}][text]" value="{{ $option->text ?? '' }}" placeholder="Option text" />
            <label>
              <input type="radio" name="correct_option" value="{{ $index }}" @checked((int) $correctValue === $index) />
              Correct
            </label>
            <button type="button" class="btn-outline remove-option">Remove</button>
          </div>
        @endforeach
      </div>
      <button type="button" class="btn-outline" id="add-option" style="margin-top:12px;">Add option</button>

      <div class="qb-actions" style="margin-top:24px;">
        <button class="btn-primary-dark" type="submit">{{ $question->exists ? 'Update MCQ' : 'Create MCQ' }}</button>
      </div>
    </form>
  </div>
@endsection

@push('scripts')
  <script>
    const optionsList = document.getElementById('options-list');
    const addOption = document.getElementById('add-option');

    const reindexOptions = () => {
      optionsList.querySelectorAll('.admin-option').forEach((row, idx) => {
        const orderInput = row.querySelector('.option-order');
        orderInput.name = `options[${idx}][order]`;
        orderInput.value = idx + 1;

        const textInput = row.querySelector('input[type="text"]');
        textInput.name = `options[${idx}][text]`;

        const radio = row.querySelector('input[type="radio"]');
        radio.value = idx;
      });
    };

    const createOptionRow = () => {
      const row = document.createElement('div');
      row.className = 'admin-option';
      row.setAttribute('draggable', 'true');
      row.innerHTML = `
        <span class="drag-handle">::</span>
        <input type="hidden" name="options[0][order]" value="1" class="option-order" />
        <input type="text" name="options[0][text]" placeholder="Option text" />
        <label><input type="radio" name="correct_option" value="0" /> Correct</label>
        <button type="button" class="btn-outline remove-option">Remove</button>
      `;
      optionsList.appendChild(row);
      reindexOptions();
    };

    addOption.addEventListener('click', createOptionRow);

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
