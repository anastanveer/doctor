@extends('layouts.admin')

@section('title', 'REVISE MRCEM - Revision topics')
@section('page_title', 'Revision topics')
@section('page_sub', 'Manage main revision note categories.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-primary-dark" href="{{ route('admin.revision-topics.create', $examType ? ['exam_type' => $examType] : []) }}">
          <span class="btn-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </span>
          Add topic
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
        <a class="btn-outline" href="{{ route('admin.revision-topics.index') }}">Reset</a>
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
            <th style="text-align:left; padding:10px;">Name</th>
            <th style="text-align:left; padding:10px;">Exam</th>
            <th style="text-align:left; padding:10px;">Slug</th>
            <th style="text-align:left; padding:10px;">Notes</th>
            <th style="text-align:left; padding:10px;">Updated</th>
            <th style="text-align:left; padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($topics as $topic)
            <tr>
              <td style="padding:10px;">{{ $topic->name }}</td>
              <td style="padding:10px;">
                {{ $topic->exam_type === 'intermediate' ? 'MRCEM Intermediate' : 'MRCEM Primary' }}
              </td>
              <td style="padding:10px;">{{ $topic->slug }}</td>
              <td style="padding:10px;">{{ $topic->notes_count }}</td>
              <td style="padding:10px;">{{ $topic->updated_at->format('d M Y') }}</td>
              <td style="padding:10px; display:flex; gap:8px;">
                <a class="btn-outline" href="{{ route('admin.revision-topics.edit', $topic) }}">Edit</a>
                <form method="post" action="{{ route('admin.revision-topics.destroy', $topic) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="padding:12px;">No revision topics yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:18px;">
      {{ $topics->links('pagination.admin') }}
    </div>
  </div>
@endsection
