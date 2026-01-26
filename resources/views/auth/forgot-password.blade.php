@extends('layouts.app')

@section('title', 'REVISE MRCEM - Forgot password')

@section('content')
  <main class="page login-page">
    <div class="container">
      <section class="login-wrap">
        <h1 class="login-title">Reset your password</h1>
        <div class="login-line"></div>

        @if (session('status'))
          <div class="qb-card" style="margin-bottom:14px;">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="qb-card" style="margin-bottom:14px;">
            {{ $errors->first() }}
          </div>
        @endif

        <form class="login-form" action="{{ route('password.email') }}" method="post" aria-label="Password reset request">
          @csrf
          <div class="login-field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required />
          </div>

          <button type="submit" class="login-btn">Send reset link</button>

          <div class="login-bottom">
            Back to <a href="{{ route('login') }}">Sign in</a>
          </div>
        </form>
      </section>
    </div>
  </main>
@endsection
