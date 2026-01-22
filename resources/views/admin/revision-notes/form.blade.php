@extends('layouts.admin')

@section('title', 'REVISE MSRA - Revision notes')
@section('page_title', $note->exists ? 'Edit revision note' : 'Create revision note')
@section('page_sub', 'Each note appears under a topic and opens as a detailed article.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.revision-notes.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $note->exists ? route('admin.revision-notes.update', $note) : route('admin.revision-notes.store') }}">
      @csrf
      @if ($note->exists)
        @method('put')
      @endif

      <div class="qb-options" style="gap:14px;">
        <label class="qb-radio" style="gap:6px;">
          <span>Topic</span>
        </label>
        <select name="revision_topic_id" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
          @foreach ($topics as $topic)
            <option value="{{ $topic->id }}" @selected((int) old('revision_topic_id', $note->revision_topic_id) === $topic->id)>{{ $topic->name }}</option>
          @endforeach
        </select>

        <label class="qb-radio" style="gap:6px;">
          <span>Note title</span>
        </label>
        <input name="title" type="text" value="{{ old('title', $note->title) }}" placeholder="e.g. Hypertension management" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Slug (optional)</span>
        </label>
        <input name="slug" type="text" value="{{ old('slug', $note->slug) }}" placeholder="hypertension-management" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Summary (optional)</span>
        </label>
        <textarea name="summary" rows="3" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('summary', $note->summary) }}</textarea>

        <label class="qb-radio" style="gap:6px;">
          <span>Content</span>
        </label>
        <textarea name="content" rows="10" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('content', $note->content) }}</textarea>
      </div>

      <div class="qb-actions" style="margin-top:24px;">
        <button class="btn-primary-dark" type="submit">{{ $note->exists ? 'Update note' : 'Create note' }}</button>
      </div>
    </form>
  </div>
@endsection
