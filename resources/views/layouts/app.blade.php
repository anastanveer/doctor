<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'REVISE MSRA')</title>
  <link rel="stylesheet" href="{{ asset('styles/custom.css') }}" />
  @stack('head')
</head>
<body class="@yield('body_class')">
  @include('partials.header')

  @yield('content')

  @include('partials.footer')

  @stack('scripts')
</body>
</html>
