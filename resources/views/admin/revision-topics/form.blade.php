@extends('layouts.admin')

@section('title', 'REVISE MRCEM - Revision topics')
@section('page_title', $topic->exists ? 'Edit revision topic' : 'Create revision topic')
@section('page_sub', 'These appear as the main revision note categories.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.revision-topics.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $topic->exists ? route('admin.revision-topics.update', $topic) : route('admin.revision-topics.store') }}">
      @csrf
      @if ($topic->exists)
        @method('put')
      @endif

      <div class="qb-options" style="gap:14px;">
        <label class="qb-radio" style="gap:6px;">
          <span>Exam</span>
        </label>
        <select name="exam_type" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
          @foreach ($examTypes as $value => $label)
            <option value="{{ $value }}" @selected(old('exam_type', $topic->exam_type ?? $examType ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>

        <label class="qb-radio" style="gap:6px;">
          <span>Topic name</span>
        </label>
        <input name="name" type="text" value="{{ old('name', $topic->name) }}" placeholder="e.g. Cardiology" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Slug (optional)</span>
        </label>
        <input name="slug" type="text" value="{{ old('slug', $topic->slug) }}" placeholder="cardiology" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Description (optional)</span>
        </label>
        <textarea name="description" rows="4" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('description', $topic->description) }}</textarea>
      </div>

      <div class="qb-actions" style="margin-top:24px;">
        <button class="btn-primary-dark" type="submit">{{ $topic->exists ? 'Update topic' : 'Create topic' }}</button>
      </div>
    </form>
  </div>
@endsection
