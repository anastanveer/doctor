@extends('layouts.admin')

@section('title', 'REVISE MSRA - Mock MCQs')
@section('page_title', 'Mock MCQs')
@section('page_sub', 'Add MCQs for each mock paper.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions" style="display:flex; gap:10px; align-items:center;">
        <form method="get" action="{{ route('admin.mock-questions.index') }}">
          <select name="paper" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" onchange="this.form.submit()">
            <option value="">All papers</option>
            @foreach ($papers as $paper)
              <option value="{{ $paper->id }}" @selected($paperId === $paper->id)>{{ $paper->title }}</option>
            @endforeach
          </select>
        </form>
        <a class="btn-primary-dark" href="{{ route('admin.mock-questions.create', $paperId ? ['paper' => $paperId] : []) }}">
          <span class="btn-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </span>
          Add MCQ
        </a>
      </div>
    </div>

    @if (session('status'))
      <div class="qb-card" style="margin-bottom:16px;">
        {{ session('status') }}
      </div>
    @endif

    <div class="qb-card">
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left; padding:10px;">Paper</th>
            <th style="text-align:left; padding:10px;">Order</th>
            <th style="text-align:left; padding:10px;">Topic</th>
            <th style="text-align:left; padding:10px;">Stem</th>
            <th style="text-align:left; padding:10px;">Updated</th>
            <th style="text-align:left; padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($questions as $question)
            <tr>
              <td style="padding:10px;">{{ $question->paper?->title ?? 'N/A' }}</td>
              <td style="padding:10px;">{{ $question->order }}</td>
              <td style="padding:10px;">{{ $question->topic ?? 'General' }}</td>
              <td style="padding:10px;">{{ \Illuminate\Support\Str::limit($question->stem, 80) }}</td>
              <td style="padding:10px;">{{ $question->updated_at->format('d M Y') }}</td>
              <td style="padding:10px; display:flex; gap:8px;">
                <a class="btn-outline" href="{{ route('admin.mock-questions.edit', $question) }}">Edit</a>
                <form method="post" action="{{ route('admin.mock-questions.destroy', $question) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding:12px;">No mock MCQs yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:18px;">
      {{ $questions->links('pagination.admin') }}
    </div>
  </div>
@endsection
