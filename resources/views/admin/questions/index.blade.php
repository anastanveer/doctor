@extends('layouts.admin')

@section('title', 'REVISE MSRA • Question admin')
@section('page_title', 'Question bank admin')
@section('page_sub', 'Create, reorder, and track every MCQ type.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-primary-dark" href="{{ route('admin.questions.create') }}">
          <span class="btn-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </span>
          Add question
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
            <th style="text-align:left; padding:10px;">ID</th>
            <th style="text-align:left; padding:10px;">Topic</th>
            <th style="text-align:left; padding:10px;">Type</th>
            <th style="text-align:left; padding:10px;">Stem</th>
            <th style="text-align:left; padding:10px;">Updated</th>
            <th style="text-align:left; padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($questions as $question)
            <tr>
              <td style="padding:10px;">#{{ $question->id }}</td>
              <td style="padding:10px;">{{ $question->topic?->name ?? '—' }}</td>
              <td style="padding:10px;">{{ $question->type }}</td>
              <td style="padding:10px;">{{ \Illuminate\Support\Str::limit($question->stem, 80) }}</td>
              <td style="padding:10px;">{{ $question->updated_at->format('d M Y') }}</td>
              <td style="padding:10px; display:flex; gap:8px;">
                <a class="btn-outline" href="{{ route('admin.questions.edit', $question) }}">
                  <span class="btn-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                      <path d="M4 16.5V20h3.5L18 9.5 14.5 6 4 16.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                      <path d="M13.5 7 17 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                  </span>
                  Edit
                </a>
                <form method="post" action="{{ route('admin.questions.destroy', $question) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">
                    <span class="btn-ico" aria-hidden="true">
                      <svg viewBox="0 0 24 24" fill="none">
                        <path d="M6 7h12M9 7V5h6v2M8 7l1 12h6l1-12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                      </svg>
                    </span>
                    Delete
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div style="margin-top:18px;">
      {{ $questions->links('pagination.admin') }}
    </div>
  </div>
@endsection
