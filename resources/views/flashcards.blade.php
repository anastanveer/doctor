@extends('layouts.app')

@section('title', 'REVISE MSRA • Flashcards')

@section('content')
  <section class="page-hero page-hero--purple">
    <div class="page-hero__inner">
      <h1 class="page-hero__title">Flashcards</h1>
    </div>
  </section>

  <main class="page fc-page">
    <div class="container fc-layout">
      <section class="fc-copy">
        <h2 class="fc-h2">
          🚀 ReviseMSRA High-Yield Flashcards: Active recall for MSRA success
        </h2>

        <ul class="fc-bullets">
          <li>The MSRA is <strong>more competitive than ever</strong>. With competition ratios at record highs, a single mark could mean the difference between securing your dream NHS training post, or waiting another year.</li>
          <li>The ReviseMSRA High-Yield Flashcards will help you lock in the <strong>most critical MSRA concepts and guidelines</strong>, with <strong>exam-focused active recall</strong> — giving you the knowledge to maximise your score and outperform your competition.</li>
          <li class="fc-links">
            <a href="#">See Flashcard Demo</a> | <a href="#">See Before You Subscribe</a>
          </li>
        </ul>

        <div class="fc-section">
          <h3>🧠 4,000+ Flashcards Focused on the Most Commonly Tested MSRA Concepts</h3>
          <ul class="fc-checks">
            <li>Fully aligned to <strong>NICE</strong> and <strong>UK clinical guidelines</strong> — exactly what the MSRA tests.</li>
            <li>Focus on <strong>first-line investigations</strong>, management, and classic clinical presentations.</li>
            <li>Built on <strong>Active Recall</strong> — an evidence-based revision strategy to maximise retention & exam results.</li>
            <li>The perfect addition to any question bank — <strong>lock in the knowledge you need</strong> for exam day.</li>
          </ul>
        </div>

        <div class="fc-section">
          <h3>⚡ Turn Knowledge Into Second Nature</h3>
          <p class="fc-muted">
            Every flashcard is designed to ensure you <strong>master the key facts</strong> that drive MSRA questions — turning guidelines into instinctive answers you can apply under exam pressure.
          </p>
        </div>

        <div class="fc-section">
          <h3>🔒 Our Risk-Free Guarantee</h3>
          <p class="fc-muted">MSRA success guaranteed or 100% money back*</p>
        </div>

        <div class="fc-section">
          <h3>💬 Your Feedback</h3>
          <div class="fc-stars">★★★★★</div>
          <p class="fc-quote">
            “I've only just started using the flashcards, but it's been a great boost to my revision.
            Easy to get through and helping me lock in the most important exam concepts.”
          </p>
        </div>

        <div class="fc-callout">
          ⚡ Subscribe to our MSRA High-Yield Flashcards — secure your access today and focus on clinchers and frequently examined facts ⚡
        </div>
      </section>

      <aside class="fc-aside">
        <form class="sub-card" action="#" method="post">
          <div class="sub-head">
            <div>
              <div class="sub-kicker">Choose membership</div>
              <div class="sub-title">Unlock Flashcards Access</div>
            </div>
            <div class="sub-badge">Secure</div>
          </div>

          <div class="sub-options">
            <label class="sub-option is-selected">
              <input type="radio" name="plan" checked />
              <span class="sub-row">
                <span class="sub-main">
                  <span class="sub-name">3 months</span>
                  <span class="sub-price">
                    <s>£49.99</s> <strong>£29.97</strong>
                  </span>
                </span>
                <span class="sub-meta">33p per day</span>
              </span>
            </label>

            <label class="sub-option">
              <input type="radio" name="plan" />
              <span class="sub-row">
                <span class="sub-main">
                  <span class="sub-name">6 months</span>
                  <span class="sub-price">
                    <s>£89.99</s> <strong>£35.97</strong>
                  </span>
                </span>
                <span class="sub-meta">
                  20p per day
                  <span class="tag tag--hot">MOST POPULAR</span>
                </span>
              </span>
            </label>

            <label class="sub-option">
              <input type="radio" name="plan" />
              <span class="sub-row">
                <span class="sub-main">
                  <span class="sub-name">12 months</span>
                  <span class="sub-price">
                    <s>£119.99</s> <strong>£54.97</strong>
                  </span>
                </span>
                <span class="sub-meta">
                  15p per day
                  <span class="tag tag--best">BEST VALUE</span>
                </span>
              </span>
            </label>
          </div>

          <label class="sub-terms">
            <input type="checkbox" />
            <span>I agree to the <a href="{{ route('terms') }}">terms and conditions</a> and <a href="{{ route('privacy') }}">privacy policy</a></span>
          </label>

          <button class="sub-btn" type="button">Register now</button>

          <div class="sub-foot">
            <span class="sub-foot-dot"></span>
            Encrypted checkout • Cancel anytime
          </div>
        </form>
      </aside>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    document.querySelectorAll('.sub-option input').forEach((input) => {
      input.addEventListener('change', () => {
        document.querySelectorAll('.sub-option').forEach(o => o.classList.remove('is-selected'));
        input.closest('.sub-option').classList.add('is-selected');
      });
    });
  </script>
@endpush
