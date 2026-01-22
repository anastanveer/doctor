@extends('layouts.admin')

@section('title', 'REVISE MSRA - Mock papers')
@section('page_title', 'Mock papers')
@section('page_sub', 'Create and manage mock papers for the MCQ sessions.')

@section('content')
  <div class="admin-panel">
    <div class="account-head" style="margin-bottom:18px;">
      <div class="account-actions">
        <a class="btn-primary-dark" href="{{ route('admin.mock-papers.create') }}">
          <span class="btn-ico" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
              <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
          </span>
          Add mock paper
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
            <th style="text-align:left; padding:10px;">Title</th>
            <th style="text-align:left; padding:10px;">Slug</th>
            <th style="text-align:left; padding:10px;">Order</th>
            <th style="text-align:left; padding:10px;">Duration</th>
            <th style="text-align:left; padding:10px;">Questions</th>
            <th style="text-align:left; padding:10px;">Status</th>
            <th style="text-align:left; padding:10px;">Updated</th>
            <th style="text-align:left; padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($papers as $paper)
            <tr>
              <td style="padding:10px;">{{ $paper->title }}</td>
              <td style="padding:10px;">{{ $paper->slug }}</td>
              <td style="padding:10px;">{{ $paper->order }}</td>
              <td style="padding:10px;">{{ $paper->duration_minutes }} min</td>
              <td style="padding:10px;">{{ $paper->questions_count }}</td>
              <td style="padding:10px;">{{ $paper->is_active ? 'Active' : 'Hidden' }}</td>
              <td style="padding:10px;">{{ $paper->updated_at->format('d M Y') }}</td>
              <td style="padding:10px; display:flex; gap:8px;">
                <a class="btn-outline" href="{{ route('admin.mock-papers.edit', $paper) }}">Edit</a>
                <form method="post" action="{{ route('admin.mock-papers.destroy', $paper) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="padding:12px;">No mock papers yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div style="margin-top:18px;">
      {{ $papers->links('pagination.admin') }}
    </div>
  </div>
@endsection
