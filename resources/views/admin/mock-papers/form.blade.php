@extends('layouts.admin')

@section('title', 'REVISE MRCEM - Mock papers')
@section('page_title', $paper->exists ? 'Edit mock paper' : 'Create mock paper')
@section('page_sub', 'Set the paper order, duration, and description.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-outline" href="{{ route('admin.mock-papers.index') }}">Back to list</a>
      </div>
    </div>

    <form class="qb-card" method="post" action="{{ $paper->exists ? route('admin.mock-papers.update', $paper) : route('admin.mock-papers.store') }}">
      @csrf
      @if ($paper->exists)
        @method('put')
      @endif

      <div class="qb-options" style="gap:14px;">
        <label class="qb-radio" style="gap:6px;">
          <span>Exam</span>
        </label>
        <select name="exam_type" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
          @foreach ($examTypes as $value => $label)
            <option value="{{ $value }}" @selected(old('exam_type', $paper->exam_type ?? $examType ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>

        <label class="qb-radio" style="gap:6px;">
          <span>Paper title</span>
        </label>
        <input name="title" type="text" value="{{ old('title', $paper->title) }}" placeholder="Mock paper 1 - Clinical problem solving" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Slug (optional)</span>
        </label>
        <input name="slug" type="text" value="{{ old('slug', $paper->slug) }}" placeholder="mock-paper-1" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Order (display number)</span>
        </label>
        <input name="order" type="number" min="1" value="{{ old('order', $paper->order ?? 1) }}" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Duration (minutes)</span>
        </label>
        <input name="duration_minutes" type="number" min="1" value="{{ old('duration_minutes', $paper->duration_minutes ?? 75) }}" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

        <label class="qb-radio" style="gap:6px;">
          <span>Description (optional)</span>
        </label>
        <textarea name="description" rows="4" style="border-radius:8px; border:1px solid var(--border); padding:12px;">{{ old('description', $paper->description) }}</textarea>
      </div>

      <div class="qb-actions" style="margin-top:24px;">
        <button class="btn-primary-dark" type="submit">{{ $paper->exists ? 'Update paper' : 'Create paper' }}</button>
        <label class="qb-radio" style="gap:6px;">
          <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $paper->is_active ?? true)) />
          Active
        </label>
      </div>
    </form>
  </div>
@endsection
