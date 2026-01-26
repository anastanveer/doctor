@extends('layouts.admin')

@section('title', 'REVISE MRCEM - Revision notes')
@section('page_title', 'Revision notes')
@section('page_sub', 'Create the subtopics and in-depth content for each topic.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-primary-dark" href="{{ route('admin.revision-notes.create', $examType ? ['exam_type' => $examType] : []) }}">
          <span class="btn-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </span>
          Add note
        </a>
      </div>
    </div>

    <form method="get" class="qb-card" style="margin-bottom:16px; display:flex; gap:12px; align-items:center;">
      <label class="qb-radio" style="gap:6px; margin:0;">
        <span>Exam</span>
      </label>
      <select name="exam_type" style="height:40px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
        <option value="">All</option>
        @foreach ($examTypes as $value => $label)
          <option value="{{ $value }}" @selected($examType === $value)>{{ $label }}</option>
        @endforeach
      </select>
      <button class="btn-outline" type="submit">Filter</button>
      @if ($examType)
        <a class="btn-outline" href="{{ route('admin.revision-notes.index') }}">Reset</a>
      @endif
    </form>

    @if (session('status'))
      <div class="qb-card" style="margin-bottom:16px;">
        {{ session('status') }}
      </div>
    @endif

    <div class="qb-card">
      <table style="width:100%; border-collapse:collapse;">
        <thead>
          <tr>
            <th style="text-align:left; padding:10px;">Title</th>
            <th style="text-align:left; padding:10px;">Topic</th>
            <th style="text-align:left; padding:10px;">Exam</th>
            <th style="text-align:left; padding:10px;">Slug</th>
            <th style="text-align:left; padding:10px;">Updated</th>
            <th style="text-align:left; padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($notes as $note)
            <tr>
              <td style="padding:10px;">{{ $note->title }}</td>
              <td style="padding:10px;">{{ $note->topic?->name ?? 'N/A' }}</td>
              <td style="padding:10px;">
                @if ($note->topic)
                  {{ $note->topic->exam_type === 'intermediate' ? 'MRCEM Intermediate' : 'MRCEM Primary' }}
                @else
                  N/A
                @endif
              </td>
              <td style="padding:10px;">{{ $note->slug }}</td>
              <td style="padding:10px;">{{ $note->updated_at->format('d M Y') }}</td>
              <td style="padding:10px; display:flex; gap:8px;">
                <a class="btn-outline" href="{{ route('admin.revision-notes.edit', $note) }}">Edit</a>
                <form method="post" action="{{ route('admin.revision-notes.destroy', $note) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding:12px;">No revision notes yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:18px;">
      {{ $notes->links('pagination.admin') }}
    </div>
  </div>
@endsection
