@extends('layouts.app')

@section('title', 'REVISE MSRA - Payment cancelled')

@section('content')
  <main class="page account-page">
    <div class="account-wrap">
      <div class="qb-card">
        <h2 style="margin:0 0 10px;">Payment cancelled</h2>
        <p style="margin:0 0 14px; color:rgba(17,24,39,.7);">
          Your checkout was cancelled. You can try again at any time.
        </p>
        <a class="btn-primary-dark" href="{{ route('subscribe') }}">Back to plans</a>
      </div>
    </div>
  </main>
@endsection
