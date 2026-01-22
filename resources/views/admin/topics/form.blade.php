@extends('layouts.admin')

@section('title', 'REVISE MSRA - Question topics')
@section('page_title', $topic->exists ? 'Edit question topic' : 'Create question topic')
@section('page_sub', 'These topics appear in the MCQ question bank.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.topics.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $topic->exists ? route('admin.topics.update', $topic) : route('admin.topics.store') }}">
      @csrf
      @if ($topic->exists)
        @method('put')
      @endif

      <div class="qb-options" style="gap:14px;">
        <label class="qb-radio" style="gap:6px;">
          <span>Topic name</span>
        </label>
        <input name="name" type="text" value="{{ old('name', $topic->name) }}" placeholder="e.g. Cardiology" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Slug (optional)</span>
        </label>
        <input name="slug" type="text" value="{{ old('slug', $topic->slug) }}" placeholder="cardiology" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />
      </div>

      <div class="qb-actions" style="margin-top:24px;">
        <button class="btn-primary-dark" type="submit">{{ $topic->exists ? 'Update topic' : 'Create topic' }}</button>
      </div>
    </form>
  </div>
@endsection
