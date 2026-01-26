<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin • REVISE MRCEM')</title>
  <link rel="stylesheet" href="{{ asset('styles/custom.css') }}" />
  @stack('head')
</head>
<body class="admin-body">
  <div class="admin-shell">
    @include('admin.partials.sidebar')
    <div class="admin-main">
      <header class="admin-topbar">
        <div>
          <h1 class="admin-title">@yield('page_title', 'Admin dashboard')</h1>
          <p class="admin-sub">@yield('page_sub', 'Monitor growth, subscriptions, and performance.') </p>
        </div>
        <div class="admin-actions">
          <form method="post" action="{{ route('admin.logout') }}">
            @csrf
            <button class="btn-outline" type="submit">Sign out</button>
          </form>
        </div>
      </header>

      <div class="admin-content">
        @yield('content')
      </div>
    </div>
  </div>
  @stack('scripts')
</body>
</html>
