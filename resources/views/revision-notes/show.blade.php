@extends('layouts.app')

@section('title', 'REVISE MRCEM - Revision notes')

@section('content')
  <section class="page-hero page-hero--green">
    <div class="page-hero__inner">
      <div class="rn-breadcrumb">
        <a href="{{ route('revision-notes') }}">Revision notes</a>
        <span>/</span>
        <a href="{{ route('revision-notes.topic', $topic->slug ?: $topic->id) }}">{{ $topic->name }}</a>
        <span>/</span>
        <span>{{ $note->title }}</span>
      </div>
      <h1 class="page-hero__title">{{ $note->title }}</h1>
      <p class="page-hero__sub">Topic: {{ $topic->name }}</p>
    </div>
  </section>

  <main class="page rn-page">
    <div class="container rn-detail">
      <article class="rn-article">
        @if ($note->summary)
          <p class="rn-article__lead">{{ $note->summary }}</p>
        @endif
        <div class="rn-article__body">{!! nl2br(e($note->content)) !!}</div>
      </article>

      @if ($related->isNotEmpty())
        <aside class="rn-related">
          <h3>Related subtopics</h3>
          <ul>
            @foreach ($related as $item)
              <li>
                <a href="{{ route('revision-notes.show', [$topic->slug ?: $topic->id, $item->slug ?: $item->id]) }}">{{ $item->title }}</a>
              </li>
            @endforeach
          </ul>
        </aside>
      @endif
    </div>
  </main>
@endsection
