@extends('layouts.admin')

@section('title', 'REVISE MRCEM • Users')
@section('page_title', 'Users')
@section('page_sub', 'Track account status, subscriptions, and retention.')

@section('content')
  <div class="admin-panel">
    <div class="admin-card">
      <div class="admin-card__head">
        <h3>All users</h3>
      </div>

      <div class="admin-table">
        <div class="admin-row admin-row--head">
          <span>Name</span>
          <span>Email</span>
          <span>Plan</span>
          <span>Expiry</span>
          <span>Status</span>
        </div>
        @foreach ($users as $user)
          @php
            $subscription = $user->subscriptions->sortByDesc('expires_at')->first();
          @endphp
          <div class="admin-row">
            <span>{{ $user->name }}</span>
            <span>{{ $user->email }}</span>
            <span>{{ $subscription?->plan?->name ?? '—' }}</span>
            <span>{{ $subscription?->expires_at?->format('d M Y') ?? '—' }}</span>
            <span class="status-pill {{ $subscription?->status === 'expired' ? 'status-pill--muted' : '' }}">
              {{ $subscription?->status ?? 'inactive' }}
            </span>
          </div>
        @endforeach
      </div>
    </div>

    <div style="margin-top:18px;">
      {{ $users->links('pagination.admin') }}
    </div>
  </div>
@endsection
