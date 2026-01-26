@extends('layouts.app')

@section('title', 'REVISE MRCEM • Reviews')

@section('content')
  <main class="page reviews-page">
    <section class="reviews-hero">
      <div class="container reviews-hero__inner">
        <div class="reviews-search">
          <span class="reviews-search__icon" aria-hidden="true">🔍</span>
          <input type="text" placeholder="Search reviews by name or hospital..." />
        </div>
        <h1 class="reviews-title">Revise MRCEM Wall of Love</h1>
        <p class="reviews-sub">
          Real feedback from doctors who used Revise MRCEM to secure their top choices.
        </p>
        <a class="reviews-cta" href="#">Share your review</a>
      </div>
    </section>

    <section class="reviews-grid">
      <div class="container">
        <div class="reviews-columns">
          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">A</div>
              <div>
                <div class="review-name">Dr A. Chowdhury</div>
                <div class="review-meta">Emergency Medicine Training</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              The question bank felt closest to the real MRCEM. I used the notes daily and jumped into mocks in week two.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">L</div>
              <div>
                <div class="review-name">Lewis Hall</div>
                <div class="review-meta">CST Applicant</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              Scored 602 and secured a CST interview. The professional dilemmas breakdowns were a game-changer.
            </p>
          </article>

          <article class="review-card review-card--image">
            <div class="review-card__head">
              <div class="review-avatar">S</div>
              <div>
                <div class="review-name">Dr S. Malik</div>
                <div class="review-meta">FY2</div>
              </div>
            </div>
            <div class="review-media" role="img" aria-label="Placeholder 260 by 160">260 x 160</div>
            <p class="review-text">
              Loved the completion tracking and short daily sessions. It kept me consistent through a busy rota.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">D</div>
              <div>
                <div class="review-name">Dessom A‑Y</div>
                <div class="review-meta">Anaesthetics</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              I used only Revise MRCEM and scored 606. The mini mocks were perfect for timed practice.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">M</div>
              <div>
                <div class="review-name">Dr M. Tanveer</div>
                <div class="review-meta">Radiology</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              The dashboard highlighted weak topics fast. My revision became focused instead of random.
            </p>
          </article>

          <article class="review-card review-card--image">
            <div class="review-card__head">
              <div class="review-avatar">H</div>
              <div>
                <div class="review-name">Dr H. Rao</div>
                <div class="review-meta">ACCS</div>
              </div>
            </div>
            <div class="review-media" role="img" aria-label="Placeholder 260 by 180">260 x 180</div>
            <p class="review-text">
              The explanations are concise and high-yield. I stopped using other resources.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">K</div>
              <div>
                <div class="review-name">Dr K. Patel</div>
                <div class="review-meta">IMT</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              The flashcards are sharp and easy to review on a commute. Consistency paid off.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">T</div>
              <div>
                <div class="review-name">Dr T. Ahmed</div>
                <div class="review-meta">MRCEM Candidate</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              The question explanations feel like a mini‑lesson. Saved me hours of note‑making.
            </p>
          </article>

          <article class="review-card review-card--image">
            <div class="review-card__head">
              <div class="review-avatar">R</div>
              <div>
                <div class="review-name">Dr R. Singh</div>
                <div class="review-meta">Paediatrics</div>
              </div>
            </div>
            <div class="review-media" role="img" aria-label="Placeholder 260 by 150">260 x 150</div>
            <p class="review-text">
              Mock papers were closest to the real timing. Gave me calm on exam day.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">N</div>
              <div>
                <div class="review-name">Dr N. Farooq</div>
                <div class="review-meta">Foundation</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              Clean interface, no distractions, just focus. The completion tracker kept me accountable.
            </p>
          </article>

          <article class="review-card">
            <div class="review-card__head">
              <div class="review-avatar">Z</div>
              <div>
                <div class="review-name">Dr Z. Khan</div>
                <div class="review-meta">CST</div>
              </div>
            </div>
            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              The revision plan inside the dashboard helped me schedule every week right up to exam day.
            </p>
          </article>
        </div>
      </div>
    </section>
  </main>
@endsection
