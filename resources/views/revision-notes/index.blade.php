@extends('layouts.app')

@section('title', 'REVISE MSRA - Revision notes')

@section('content')
  <section class="page-hero page-hero--green">
    <div class="page-hero__inner">
      <h1 class="page-hero__title">Revision notes</h1>
      <p class="page-hero__sub">Choose a topic to open related subtopics and detailed notes.</p>
    </div>
  </section>

  <main class="page rn-page">
    <div class="container rn-wrap">
      <div class="rn-search">
        <input type="text" placeholder="Start typing to find a revision note" />
      </div>

      @php
        $iconMap = [
          'professional-dilemmas' => '&#x2696;',
          'cardiology' => '&#x2764;',
          'dermatology' => '&#x1F9FC;',
          'endocrinology' => '&#x1F9E0;',
          'ent' => '&#x1F442;',
          'gastroenterology' => '&#x1F9C3;',
          'general-surgery' => '&#x1F9E4;',
          'haematology' => '&#x1FA78;',
          'infectious-disease' => '&#x1F9A0;',
          'neurology' => '&#x1F9E0;',
          'obs-gynae' => '&#x1F930;',
          'ophthalmology' => '&#x1F441;',
          'paediatrics' => '&#x1F9D2;',
          'pharmacology' => '&#x1F48A;',
          'psychiatry' => '&#x1F9D1;&#x200D;&#x2695;&#xFE0F;',
          'renal' => '&#x1F9FE;',
          'respiratory' => '&#x1FAC1;',
          'rheumatology-msk' => '&#x1F9B4;',
          'urology' => '&#x1F9EA;',
        ];
      @endphp

      <section class="rn-grid">
        @forelse ($topics as $topic)
          @php
            $icon = $iconMap[$topic->slug] ?? '&#x1F4D8;';
          @endphp
          <a class="rn-tile" href="{{ route('revision-notes.topic', $topic) }}">
            <span class="rn-ico">{!! $icon !!}</span>
            <span class="rn-name">{{ $topic->name }}</span>
          </a>
        @empty
          <div class="rn-empty">
            <h3>No revision topics yet</h3>
            <p>Ask an admin to add topics and notes to get started.</p>
          </div>
        @endforelse
      </section>
    </div>
  </main>
@endsection
