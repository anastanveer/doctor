@extends('layouts.admin')

@section('title', 'REVISE MRCEM • Coupons')
@section('page_title', 'Coupons')
@section('page_sub', 'Create discount codes for subscriptions and campaigns.')

@section('content')
  <div class="admin-panel">
    @if (session('status'))
      <div class="qb-card" style="margin-bottom:16px;">
        {{ session('status') }}
      </div>
    @endif

    <div class="admin-grid">
      <div class="admin-card">
        <div class="admin-card__head">
          <h3>Create coupon</h3>
        </div>
        <form method="post" action="{{ route('admin.coupons.store') }}" class="qb-options">
          @csrf
          <label class="qb-radio" style="gap:6px;"><span>Code</span></label>
          <input type="text" name="code" placeholder="REV20" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <label class="qb-radio" style="gap:6px; margin-top:8px;"><span>Type</span></label>
          <select name="type" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;">
            <option value="percent">Percent</option>
            <option value="fixed">Fixed</option>
          </select>

          <label class="qb-radio" style="gap:6px; margin-top:8px;"><span>Value</span></label>
          <input type="number" name="value" placeholder="20" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <label class="qb-radio" style="gap:6px; margin-top:8px;"><span>Max uses</span></label>
          <input type="number" name="max_uses" placeholder="50" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <label class="qb-radio" style="gap:6px; margin-top:8px;"><span>Expiry date</span></label>
          <input type="date" name="expires_at" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <div class="qb-actions" style="margin-top:16px;">
            <button class="btn-primary-dark" type="submit">Create coupon</button>
          </div>
        </form>
      </div>

      <div class="admin-card">
        <div class="admin-card__head">
          <h3>Active coupons</h3>
        </div>
        <div class="admin-table">
          <div class="admin-row admin-row--head">
            <span>Code</span>
            <span>Type</span>
            <span>Value</span>
            <span>Uses</span>
            <span>Expiry</span>
            <span></span>
          </div>
          @foreach ($coupons as $coupon)
            <div class="admin-row">
              <span>{{ $coupon->code }}</span>
              <span>{{ strtoupper($coupon->type) }}</span>
              <span>{{ $coupon->value }}</span>
              <span>{{ $coupon->uses }} / {{ $coupon->max_uses ?? '∞' }}</span>
              <span>{{ $coupon->expires_at?->format('d M Y') ?? '—' }}</span>
              <span>
                <form method="post" action="{{ route('admin.coupons.destroy', $coupon) }}">
                  @csrf
                  @method('delete')
                  <button class="btn-outline" type="submit">Delete</button>
                </form>
              </span>
            </div>
          @endforeach
        </div>
        <div style="margin-top:16px;">
          {{ $coupons->links('pagination.admin') }}
        </div>
      </div>
    </div>
  </div>
@endsection
