<!-- Header -->
@php
  $guestRoutes = ['home', 'login', 'register', 'free-resources', 'about', 'reviews', 'admin.login'];
  $showGuestHeader = !auth()->check();
@endphp

<header class="site-header">
  <div class="header-inner">
    <a class="brand" href="{{ $showGuestHeader ? route('home') : route('dashboard') }}">
      <span class="brand-mark">R</span>
      <span class="brand-text">REVISE<span class="brand-strong">MSRA</span></span>
    </a>

    @if ($showGuestHeader)
      <nav class="top-nav">
        <a href="{{ route('reviews') }}" class="nav-item {{ request()->routeIs('reviews') ? 'is-active' : '' }}">Your Reviews</a>
        <a href="{{ route('free-resources') }}" class="nav-item {{ request()->routeIs('free-resources') ? 'is-active' : '' }}">Free MSRA Resources</a>
        <a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'is-active' : '' }}">About</a>

        <a href="{{ route('login') }}" class="nav-item nav-cta nav-cta--light {{ request()->routeIs('login') ? 'is-active' : '' }}">Sign in</a>
        <a href="{{ route('register') }}" class="nav-item nav-cta nav-cta--dark {{ request()->routeIs('register') ? 'is-active' : '' }}">Sign up</a>
      </nav>
    @else
      <nav class="top-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" aria-label="Home">
          <span class="nav-ico" aria-hidden="true">⌂</span>
        </a>
        <a href="{{ route('question-bank') }}" class="nav-item {{ request()->routeIs('question-bank') ? 'is-active' : '' }}">Question bank</a>
        <a href="{{ route('revision-notes') }}" class="nav-item {{ request()->routeIs('revision-notes*') ? 'is-active' : '' }}">Revision notes</a>
        <a href="{{ route('flashcards') }}" class="nav-item {{ request()->routeIs('flashcards') ? 'is-active' : '' }}">Flashcards</a>
        <a href="{{ route('mock-papers') }}" class="nav-item {{ request()->routeIs('mock-papers*') ? 'is-active' : '' }}">Mock papers</a>
      </nav>

      @php
        $nameParts = preg_split('/\s+/', trim(auth()->user()->name ?? ''));
        $initials = collect($nameParts)->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->join(' ');
      @endphp
      <details class="profile-menu">
        <summary class="profile-chip" title="Profile">{{ $initials ?: 'U' }}</summary>
        <div class="profile-dropdown">
          <a href="{{ route('account') }}">Account</a>
          <a href="{{ route('support') }}">Support</a>
          @if (!auth()->user()->hasActiveSubscription())
            <a href="{{ route('subscribe') }}">Subscription</a>
          @endif
          <form method="post" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="profile-signout">Sign out</button>
          </form>
        </div>
      </details>
    @endif
  </div>
</header>
