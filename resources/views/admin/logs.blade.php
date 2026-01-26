@extends('layouts.admin')

@section('title', 'REVISE MRCEM - Error logs')
@section('page_title', 'Error logs')
@section('page_sub', 'Latest application errors and warnings.')

@section('content')
  <div class="admin-panel">
    <div class="admin-card">
      <div class="admin-card__head">
        <h3>Latest logs</h3>
        <span class="admin-badge">{{ $errorCount }} errors</span>
      </div>

      @if (empty($lines))
        <div class="admin-row" style="background:#fff;">
          <span>No logs found. Path: {{ $logPath }}</span>
        </div>
      @else
        <div style="max-height:520px; overflow:auto; border-radius:12px; border:1px solid rgba(15,23,42,.1); background:#0b0b0c; color:#e2e8f0; padding:14px; font-size:12px; line-height:1.6;">
          @foreach ($lines as $line)
            <div style="white-space:pre-wrap;">{{ $line }}</div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection
