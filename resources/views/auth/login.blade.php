@extends('layouts.app')

@section('title', 'REVISE MRCEM - Sign in')

@section('content')
  <main class="page login-page">
    <div class="container">
      <section class="login-wrap">
        <h1 class="login-title">Sign in</h1>
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

        <form class="login-form" action="{{ route('login.submit') }}" method="post" aria-label="Sign in form">
          @csrf
          <div class="login-field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="" required />
          </div>

          <div class="login-field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="" required />
          </div>

          <a href="{{ route('password.request') }}" class="login-forgot">Forgotten your password?</a>

          <button type="submit" class="login-btn">Sign in</button>

          <div class="login-bottom">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
          </div>
        </form>
      </section>
    </div>
  </main>
@endsection
