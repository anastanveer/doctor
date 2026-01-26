@extends('layouts.app')

@section('title', 'REVISE MRCEM - Reset password')

@section('content')
  <main class="page login-page">
    <div class="container">
      <section class="login-wrap">
        <h1 class="login-title">Create a new password</h1>
        <div class="login-line"></div>

        @if ($errors->any())
          <div class="qb-card" style="margin-bottom:14px;">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="login-form" action="{{ route('password.update') }}" method="post" aria-label="Set a new password">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}" />

          <div class="login-field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" placeholder="you@example.com" required />
          </div>

          <div class="login-field">
            <label for="password">New password</label>
            <input id="password" name="password" type="password" placeholder="********" required />
          </div>

          <div class="login-field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="********" required />
          </div>

          <button type="submit" class="login-btn">Update password</button>

          <div class="login-bottom">
            Remembered it? <a href="{{ route('login') }}">Sign in</a>
          </div>
        </form>
      </section>
    </div>
  </main>
@endsection
