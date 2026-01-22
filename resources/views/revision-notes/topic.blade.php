@extends('layouts.app')

@section('title', 'REVISE MSRA - Revision notes')

@section('content')
  <section class="page-hero page-hero--green">
    <div class="page-hero__inner">
      <div class="rn-breadcrumb">
        <a href="{{ route('revision-notes') }}">Revision notes</a>
        <span>/</span>
        <span>{{ $topic->name }}</span>
      </div>
      <h1 class="page-hero__title">{{ $topic->name }}</h1>
      <p class="page-hero__sub">
        {{ $topic->description ?: 'Pick a subtopic to open a detailed, blog-style note.' }}
      </p>
    </div>
  </section>

  <main class="page rn-page">
    <div class="container rn-wrap">
      <section class="rn-grid">
        @forelse ($notes as $note)
          <a class="rn-tile" href="{{ route('revision-notes.show', [$topic, $note]) }}">
            <span class="rn-ico">&#x1F4DD;</span>
            <span class="rn-name">{{ $note->title }}</span>
          </a>
        @empty
          <div class="rn-empty">
            <h3>No subtopics yet</h3>
            <p>Add notes for this topic in the admin panel.</p>
          </div>
        @endforelse
      </section>
    </div>
  </main>
@endsection
