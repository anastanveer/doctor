@extends('layouts.app')

@section('title', 'REVISE MRCEM • Admin login')
@section('body_class', 'admin-auth')

@section('content')
  <main class="page">
    <div class="container" style="max-width:520px; padding:50px 0;">
      <section class="qb-card">
        <div class="qb-head">
          <h2 class="qb-title">Admin sign in</h2>
          <p class="qb-sub">Secure access to the MRCEM control center</p>
        </div>

        @if ($errors->any())
          <div class="qb-card" style="background:#fff5f5; border-color:#fecaca;">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="post" action="{{ route('admin.login.submit') }}" class="qb-options">
          @csrf
          <label class="qb-radio" style="gap:6px;">
            <span>Email</span>
          </label>
          <input type="email" name="email" required placeholder="admin@revise-mrcem.com" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <label class="qb-radio" style="gap:6px; margin-top:8px;">
            <span>Password</span>
          </label>
          <input type="password" name="password" required placeholder="••••••••" style="height:44px; border-radius:8px; border:1px solid var(--border); padding:0 12px;" />

          <div class="qb-actions" style="margin-top:18px;">
            <button class="btn-primary-dark" type="submit">Sign in</button>
            <a class="btn-outline" href="{{ route('dashboard') }}">Back to site</a>
          </div>
        </form>
      </section>
    </div>
  </main>
@endsection
