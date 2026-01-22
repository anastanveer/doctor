@extends('layouts.admin')

@section('title', 'REVISE MSRA • Subscriptions')
@section('page_title', 'Subscriptions')
@section('page_sub', 'Monitor renewals, expirations, and coupon usage.')

@section('content')
  <div class="admin-panel">
    <div class="admin-card">
      <div class="admin-card__head">
        <h3>All subscriptions</h3>
      </div>

        <div class="admin-table">
          <div class="admin-row admin-row--head">
            <span>User</span>
            <span>Plan</span>
            <span>Status</span>
            <span>Started</span>
            <span>Expires</span>
            <span>Coupon</span>
          </div>
        @foreach ($subscriptions as $subscription)
          <div class="admin-row">
            <span>{{ $subscription->user?->name ?? '—' }}</span>
            <span>{{ $subscription->plan?->name ?? '—' }}</span>
            <span class="status-pill {{ $subscription->status === 'expired' ? 'status-pill--muted' : '' }}">{{ $subscription->status }}</span>
            <span>{{ $subscription->started_at->format('d M Y') }}</span>
            <span>{{ $subscription->expires_at->format('d M Y') }}</span>
            <span>{{ $subscription->coupon?->code ?? '—' }}</span>
          </div>
        @endforeach
      </div>
    </div>

    <div style="margin-top:18px;">
      {{ $subscriptions->links('pagination.admin') }}
    </div>
  </div>
@endsection
