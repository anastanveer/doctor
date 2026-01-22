@extends('layouts.app')

@section('title', 'REVISE MSRA - Payment success')

@section('content')
  <main class="page account-page">
    <div class="account-wrap">
      <div class="qb-card">
        <h2 style="margin:0 0 10px;">Payment successful</h2>
        <p style="margin:0 0 14px; color:rgba(17,24,39,.7);">
          Your subscription is being activated. You will receive a confirmation email shortly.
        </p>
        <a class="btn-primary-dark" href="{{ route('dashboard') }}">Go to dashboard</a>
      </div>
    </div>
  </main>
@endsection
