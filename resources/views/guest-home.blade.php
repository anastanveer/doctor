@extends('layouts.app')

@section('title', 'REVISE MRCEM • Home')

@push('head')
<style>
  /* ===========================
     GUEST HOME (not logged in)
     =========================== */

  .guest-page{ padding:0; }

  /* HERO */
  .guest-hero{
    background: linear-gradient(120deg, #2a1f4d 0%, #3b2b77 55%, #5133a0 100%);
    padding: 78px 0 62px;
    position: relative;
    overflow: hidden;
  }
  .guest-hero::after{
    content:"";
    position:absolute;
    inset:-180px -220px auto auto;
    width: 520px;
    height: 520px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    filter: blur(2px);
    transform: rotate(12deg);
  }
  .guest-hero__grid{
    display:grid;
    grid-template-columns: 1.05fr .95fr;
    gap: 32px;
    align-items:center;
  }
  .guest-hero__title{
    margin:0 0 18px;
    font-family:"acumin-pro-condensed", sans-serif;
    font-size: 56px;
    font-weight: 700;
    color:#f8f3ff;
    line-height:1.04;
  }
  .guest-hero__bullets{
    margin: 0 0 20px;
    padding: 0;
    list-style:none;
    display:grid;
    gap:12px;
    max-width: 560px;
  }
  .guest-hero__bullets li{
    position:relative;
    padding-left: 30px;
    color: rgba(248,243,255,.8);
    font-size: 14px;
    line-height: 1.6;
  }
  .guest-hero__bullets li::before{
    content:"\2713";
    position:absolute;
    left:0; top:1px;
    width: 20px; height: 20px;
    border-radius: 8px;
    display:grid;
    place-items:center;
    background: rgba(255,255,255,.18);
    border: 1px solid rgba(255,255,255,.2);
    font-weight: 900;
  }
  .guest-hero__actions{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin: 16px 0 16px;
  }
  .btn-primary{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    height: 44px;
    padding: 0 16px;
    border-radius: 10px;
    background:#6a4bff;
    color:#fff;
    text-decoration:none;
    font-weight: 800;
    box-shadow: 0 18px 34px rgba(22,14,54,.32);
  }
  .btn-ghost{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    height: 44px;
    padding: 0 16px;
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.28);
    color:#f8f3ff;
    text-decoration:none;
    font-weight: 800;
  }
  .guest-hero__trust{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }
  .trust-pill{
    font-size: 13px;
    color: rgba(248,243,255,.8);
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,.16);
    border: 1px solid rgba(255,255,255,.18);
  }

  /* Right orbit */
  .hero-orbit{
    position: relative;
    height: 220px;
  }
  .orbit-big{
    position:absolute;
    right: 60px;
    top: 24px;
    width: 132px; height: 132px;
    border-radius: 999px;
    background: rgba(255,255,255,.55);
    border: 3px solid rgba(255,255,255,.8);
  }
  .orbit-sm{
    position:absolute;
    width: 80px; height: 80px;
    border-radius: 999px;
    background: rgba(255,255,255,.45);
    border: 3px solid rgba(255,255,255,.8);
  }
  .orbit-sm--1{ right: 168px; top: 4px; }
  .orbit-sm--2{ right: 10px; top: 26px; }
  .orbit-sm--3{ right: 152px; top: 118px; }
  .orbit-sm--4{ right: 36px; top: 130px; }

  .hero-card{
    margin-top: 18px;
    border-radius: 14px;
    padding: 14px 14px 12px;
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(6px);
  }
  .hero-card__top{ display:flex; gap:8px; margin-bottom: 10px; }
  .hero-tag{
    font-size: 12px;
    font-weight: 900;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,.40);
    border: 1px solid rgba(0,0,0,.08);
    color:#111;
  }
  .hero-tag--alt{
    background: rgba(255,255,255,.22);
  }
  .hero-card__title{
    font-weight: 900;
    color:#f8f3ff;
    font-size: 15px;
  }
  .hero-card__text{
    margin: 6px 0 0;
    color: rgba(248,243,255,.78);
    font-size: 14px;
    line-height: 1.55;
  }

  .hero-visual{
    position: relative;
    border-radius: 18px;
    padding: 18px;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.08);
    box-shadow: 0 30px 60px rgba(19,12,46,.45);
    display:grid;
    gap:14px;
  }
  .hero-mini{
    border-radius: 14px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.12);
    color:#f8f3ff;
  }
  .hero-mini__tag{
    font-size: 11px;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(248,243,255,.7);
  }
  .hero-mini__title{
    margin: 6px 0 4px;
    font-size: 16px;
    font-weight: 800;
  }
  .hero-mini__price{
    font-size: 13px;
    color: rgba(248,243,255,.7);
  }

  /* PACKAGES */
  .guest-packages{
    padding: 34px 0 18px;
    background: #f3f0ff;
  }
  .package-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap: 18px;
    margin-bottom: 20px;
  }
  .package-lead{
    margin: 0;
    font-size: 14px;
    color: rgba(27,20,52,.68);
    max-width: 520px;
  }
  .package-grid{
    display:grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 20px;
  }
  .package-card{
    border-radius: 16px;
    padding: 18px;
    background: #fff;
    border: 1px solid rgba(27,20,52,.08);
    box-shadow: 0 22px 48px rgba(26,18,66,.12);
  }
  .package-tag{
    display:inline-flex;
    align-items:center;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(91,69,212,.12);
    color:#3b2aa0;
    border: 1px solid rgba(91,69,212,.2);
  }
  .package-title{
    margin: 12px 0 6px;
    font-size: 20px;
    font-weight: 900;
    color:#1b1434;
  }
  .package-sub{
    margin: 0 0 12px;
    color: rgba(27,20,52,.7);
    font-size: 14px;
    line-height: 1.6;
  }
  .package-list{
    list-style:none;
    margin: 0 0 14px;
    padding: 0;
    display:grid;
    gap: 8px;
  }
  .package-list li{
    position:relative;
    padding-left: 22px;
    font-size: 13px;
    color: rgba(27,20,52,.78);
  }
  .package-list li::before{
    content:"\2713";
    position:absolute;
    left:0;
    top:1px;
    font-weight: 900;
    font-size: 12px;
    color:#5b45d4;
  }
  .package-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding: 10px 12px;
    border-radius: 12px;
    background: #f6f4ff;
    border: 1px solid rgba(91,69,212,.08);
    margin-bottom: 10px;
  }
  .package-row:last-child{ margin-bottom: 0; }
  .package-duration{
    font-size: 13px;
    color: rgba(27,20,52,.7);
  }
  .package-price{
    font-size: 16px;
    font-weight: 800;
    color:#1b1434;
  }
  .package-cta{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 8px 12px;
    border-radius: 10px;
    background:#5b45d4;
    color:#fff;
    text-decoration:none;
    font-size: 13px;
    font-weight: 700;
  }

  /* VIDEO */
  .guest-video{
    padding: 34px 0 30px;
    background: #fff;
  }
  .guest-video__box{
    border-radius: 14px;
    border: 1px solid rgba(15,23,42,.12);
    overflow:hidden;
    box-shadow: 0 18px 40px rgba(15,23,42,.08);
  }
  .guest-video__media{
    background: #f2f7f5;
  }
  .guest-video__player{
    width:100%;
    height: 360px;
    display:block;
    background: #f2f7f5;
  }
  .guest-video__caption{
    padding: 14px 16px;
  }
  .guest-video__title{
    font-weight: 900;
    color:#111;
  }
  .guest-video__sub{
    margin-top: 6px;
    font-size: 14px;
    color: rgba(17,24,39,.62);
  }

  /* REVIEWS */
  .guest-reviews{ padding: 26px 0 34px; }
  .guest-h2{
    margin: 0 0 16px;
    font-family:"acumin-pro-condensed", sans-serif;
    font-size: 24px;
    font-weight: 700;
    color:#111;
    text-align:center;
  }
  .review-grid{
    display:grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 16px;
  }
  .review-card{
    border-radius: 14px;
    border: 1px solid rgba(99,215,207,.45);
    background: #fff;
    box-shadow: 0 16px 34px rgba(15,23,42,.08);
    padding: 14px;
  }
  .review-head{
    display:flex;
    gap:10px;
    align-items:center;
    margin-bottom: 10px;
  }
  .review-avatar{
    width: 34px; height: 34px;
    border-radius: 999px;
    display:grid;
    place-items:center;
    background: rgba(99,215,207,.18);
    border: 1px solid rgba(99,215,207,.35);
    font-weight: 900;
    color:#111;
    font-size: 13px;
  }
  .review-name{ font-weight: 900; font-size: 13px; color:#111; }
  .review-meta{ font-size: 12px; color: rgba(17,24,39,.55); }

  .review-stars{ color:#f4c54a; letter-spacing:1px; font-size: 14px; }
  .review-text{
    margin: 0 0 10px;
    font-size: 13px;
    color: rgba(17,24,39,.72);
    line-height: 1.6;
  }
  .review-link{
    font-size: 13px;
    color:#ff2d86;
    font-weight: 800;
    text-decoration: underline;
  }

  /* TRUST PROOF */
  .guest-proof{
    padding: 26px 0 30px;
    background: #f7fbfa;
  }
  .guest-proof__grid{
    display:grid;
    grid-template-columns: repeat(4, minmax(0,1fr));
    gap: 14px;
  }
  .proof-card{
    border-radius: 14px;
    border: 1px solid rgba(99,215,207,.35);
    background: #fff;
    box-shadow: 0 16px 34px rgba(15,23,42,.08);
    padding: 14px;
  }
  .proof-ico{
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display:grid;
    place-items:center;
    background: rgba(99,215,207,.18);
    border: 1px solid rgba(99,215,207,.35);
    color:#111;
  }
  .proof-ico svg{
    width: 18px;
    height: 18px;
    display:block;
  }
  .proof-value{
    margin-top: 10px;
    font-weight: 900;
    font-size: 24px;
    color:#111;
  }
  .count-up{
    display:inline-block;
    min-width: 72px;
    font-variant-numeric: tabular-nums;
  }
  .proof-label{
    margin-top: 6px;
    font-weight: 800;
    font-size: 13px;
    color:#111;
  }
  .proof-sub{
    margin-top: 6px;
    font-size: 12px;
    color: rgba(17,24,39,.60);
    line-height: 1.6;
  }

  /* HOW IT WORKS */
  .guest-steps{
    padding: 28px 0 34px;
    background: #fff;
  }
  .guest-h2--left{
    text-align:left;
  }
  .guest-steps__head{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:12px;
    margin-bottom: 16px;
  }
  .guest-steps__sub{
    margin:0;
    font-size: 14px;
    color: rgba(17,24,39,.65);
    max-width: 420px;
  }
  .guest-steps__grid{
    display:grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 16px;
  }
  .step-card{
    border-radius: 14px;
    border: 1px solid rgba(15,23,42,.10);
    background: #fff;
    box-shadow: 0 16px 30px rgba(15,23,42,.06);
    padding: 16px;
    display:flex;
    flex-direction:column;
    gap:8px;
  }
  .step-card--accent{
    background: rgba(99,215,207,.12);
    border-color: rgba(99,215,207,.45);
  }
  .step-top{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .step-ico{
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display:grid;
    place-items:center;
    background: #0b0b0c;
    color:#fff;
    box-shadow: 0 10px 20px rgba(15,23,42,.18);
  }
  .step-ico svg{
    width: 20px;
    height: 20px;
    display:block;
  }
  .step-num{
    height: 28px;
    padding: 0 10px;
    border-radius: 999px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    background: rgba(15,23,42,.08);
    color:#111;
    font-weight: 900;
    font-size: 11px;
    letter-spacing: .08em;
    text-transform: uppercase;
  }
  .step-title{
    margin: 2px 0 0;
    font-weight: 900;
    font-size: 14px;
    color:#111;
  }
  .step-text{
    margin-top: 6px;
    font-size: 13px;
    color: rgba(17,24,39,.70);
    line-height: 1.6;
  }

  /* MEMBERSHIP */
  .guest-plan{
    padding: 30px 0 36px;
    background: #f9f7ff;
  }
  .guest-plan__grid{
    display:grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 20px;
    align-items:center;
  }
  .guest-plan__lead{
    margin: 0;
    font-size: 14px;
    color: rgba(17,24,39,.70);
    max-width: 520px;
  }
  .guest-plan__list{
    list-style:none;
    margin: 12px 0 0;
    padding: 0;
    display:grid;
    gap: 8px;
  }
  .guest-plan__list li{
    position:relative;
    padding-left: 24px;
    font-size: 14px;
    color: rgba(17,24,39,.78);
    line-height: 1.6;
  }
  .guest-plan__list li::before{
    content:"\2713";
    position:absolute;
    left:0;
    top:2px;
    width: 16px;
    height: 16px;
    border-radius: 6px;
    display:grid;
    place-items:center;
    background: rgba(15,23,42,.08);
    font-weight: 900;
    font-size: 11px;
  }
  .plan-card{
    border-radius: 16px;
    border: 1px solid rgba(15,23,42,.12);
    background: #fff;
    box-shadow: 0 18px 36px rgba(15,23,42,.10);
    padding: 16px;
  }
  .plan-tag{
    display:inline-block;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 6px 10px;
    border-radius: 999px;
    background: rgba(91,69,212,.12);
    border: 1px solid rgba(91,69,212,.25);
    color:#3b2aa0;
  }
  .plan-title{
    margin: 10px 0 4px;
    font-weight: 900;
    font-size: 17px;
    color:#111;
  }
  .plan-price{
    font-size: 14px;
    color: rgba(17,24,39,.65);
  }
  .plan-divider{
    height: 1px;
    background: rgba(15,23,42,.08);
    margin: 12px 0;
  }
  .plan-list{
    list-style:none;
    margin: 0 0 14px;
    padding: 0;
    display:grid;
    gap: 8px;
  }
  .plan-list li{
    position:relative;
    padding-left: 22px;
    font-size: 13px;
    color: rgba(17,24,39,.75);
  }
  .plan-list li::before{
    content:"\2713";
    position:absolute;
    left:0;
    top:1px;
    font-weight: 900;
    font-size: 12px;
    color:#0b0b0c;
  }
  .plan-trust{
    margin-top: 10px;
    font-size: 12px;
    color: rgba(17,24,39,.55);
  }

  /* CTA ORANGE */
  .guest-cta{
    background: linear-gradient(120deg, #32225c 0%, #4a3192 55%, #5b45d4 100%);
    padding: 28px 0 30px;
  }
  .guest-cta__inner{ text-align:center; }
  .guest-cta__title{
    margin: 0 0 6px;
    font-family:"acumin-pro-condensed", sans-serif;
    font-size: 28px;
    font-weight: 700;
    color:#f8f3ff;
  }
  .guest-cta__sub{
    margin: 0 0 12px;
    color: rgba(248,243,255,.75);
    font-size: 14px;
  }
  .guest-cta__actions{
    display:flex;
    justify-content:center;
    gap: 10px;
    flex-wrap:wrap;
  }
  .btn-primary--dark{
    background:#5b45d4;
    color:#fff;
  }
  .btn-ghost--dark{
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.3);
    color:#f8f3ff;
  }

  /* responsive */
  @media (max-width: 1024px){
    .guest-hero__grid{ grid-template-columns: 1fr; }
    .review-grid{ grid-template-columns: 1fr; }
    .guest-video__player{ height: 240px; }
    .guest-hero__title{ font-size: 40px; }
    .guest-hero__right{ order:-1; }
    .guest-proof__grid{ grid-template-columns: repeat(2, minmax(0,1fr)); }
    .guest-steps__head{ flex-direction: column; align-items:flex-start; }
    .guest-steps__grid{ grid-template-columns: 1fr; }
    .guest-plan__grid{ grid-template-columns: 1fr; }
    .package-head{ flex-direction: column; align-items:flex-start; }
    .package-grid{ grid-template-columns: 1fr; }
  }
</style>
@endpush

@section('content')
  <main class="page guest-page">
    <section class="guest-hero">
      <div class="container guest-hero__grid">
        <div class="guest-hero__left">
          <h1 class="guest-hero__title">Pass your MRCEM exams the first time.</h1>

          <ul class="guest-hero__bullets">
            <li>Dedicated question banks for MRCEM Primary and MRCEM Intermediate.</li>
            <li>Timed and practice modes with detailed explanations.</li>
            <li>Track progress across every topic and blueprint domain.</li>
            <li>Mock papers and revision notes built for the MRCEM syllabus.</li>
          </ul>

          <div class="guest-hero__actions">
            <a class="btn-primary" href="{{ route('register') }}">Start revising now</a>
            <a class="btn-ghost" href="{{ route('login') }}">I already have an account</a>
          </div>

          <div class="guest-hero__trust">
            <span class="trust-pill">&#x2705; Instant access after payment</span>
            <span class="trust-pill">&#x1F512; Secure checkout</span>
            <span class="trust-pill">&#x2B50; 10,000+ users</span>
          </div>

          <div class="hero-card">
            <div class="hero-card__title">Two exams, two focused packages</div>
            <p class="hero-card__text">
              Choose MRCEM Primary or MRCEM Intermediate based on your exam timeline.
            </p>
          </div>
        </div>

        <div class="guest-hero__right">
          <div class="hero-visual" aria-hidden="true">
            <div class="hero-mini">
              <div class="hero-mini__tag">Primary</div>
              <div class="hero-mini__title">MRCEM Primary</div>
              <div class="hero-mini__price">From £25 per month</div>
            </div>
            <div class="hero-mini">
              <div class="hero-mini__tag">Intermediate</div>
              <div class="hero-mini__title">MRCEM Intermediate</div>
              <div class="hero-mini__price">Advanced question bank</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="guest-packages">
      <div class="container">
        <div class="package-head">
          <div>
            <h2 class="guest-h2 guest-h2--left">Choose your MRCEM package</h2>
            <p class="package-lead">
              MRCEM Primary and MRCEM Intermediate are purchased separately. Pick the plan that fits
              your exam stage and goals.
            </p>
          </div>
        </div>

        <div class="package-grid">
          <article class="package-card">
            <span class="package-tag">Most popular</span>
            <h3 class="package-title">MRCEM Primary</h3>
            <p class="package-sub">
              Foundational sciences covered in a focused, exam-aligned MCQ bank.
            </p>
            <ul class="package-list">
              <li>Anatomy, Physiology, Pharmacology, Microbiology, Pathology</li>
              <li>Evidence-based medicine and past recalls</li>
              <li>Timed exams and practice mode</li>
              <li>Detailed explanations and progress tracking</li>
            </ul>
            <div class="package-row">
              <div>
                <div class="package-duration">1 Month Access</div>
                <div class="package-price">£25</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
            <div class="package-row">
              <div>
                <div class="package-duration">3 Months Access</div>
                <div class="package-price">£45</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
            <div class="package-row">
              <div>
                <div class="package-duration">6 Months Access</div>
                <div class="package-price">£55</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
          </article>

          <article class="package-card">
            <span class="package-tag">Advanced</span>
            <h3 class="package-title">MRCEM Intermediate Question Bank</h3>
            <p class="package-sub">
              Higher-level questions covering medical and emergency specialties.
            </p>
            <ul class="package-list">
              <li>Specialty topics across cardiology, neurology, respiratory, and more</li>
              <li>Emergency presentations, trauma, and resuscitation</li>
              <li>Procedural skills and advanced airway management</li>
              <li>Detailed explanations and performance analytics</li>
            </ul>
            <div class="package-row">
              <div>
                <div class="package-duration">1 Month Access</div>
                <div class="package-price">£25</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
            <div class="package-row">
              <div>
                <div class="package-duration">3 Months Access</div>
                <div class="package-price">£45</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
            <div class="package-row">
              <div>
                <div class="package-duration">6 Months Access</div>
                <div class="package-price">£50</div>
              </div>
              <a class="package-cta" href="{{ route('register') }}">Subscribe now</a>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section class="guest-video">
      <div class="container">
        <div class="guest-video__box">
          <div class="guest-video__media">
            <video controls class="guest-video__player" preload="metadata">
              <source src="assets/intro.mp4" type="video/mp4" />
            </video>
          </div>
          <div class="guest-video__caption">
            <div class="guest-video__title">See how it works in 30 seconds</div>
            <div class="guest-video__sub">A quick tour of the platform and what you'll unlock after signup.</div>
          </div>
        </div>
      </div>
    </section>

    <section class="guest-proof">
      <div class="container">
        <div class="guest-proof__grid">
          <article class="proof-card">
            <div class="proof-ico" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 6.5C4 5.12 5.12 4 6.5 4h11C18.88 4 20 5.12 20 6.5v11c0 1.38-1.12 2.5-2.5 2.5h-11C5.12 20 4 18.88 4 17.5v-11Z" stroke="currentColor" stroke-width="1.6"/>
                <path d="M8 8.5h8M8 12h8M8 15.5h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="proof-value"><span class="count-up" data-count="3000" data-suffix="+">0</span></div>
            <div class="proof-label">MRCEM-style questions</div>
            <div class="proof-sub">Structured to mirror the exam blueprint and tone.</div>
          </article>

          <article class="proof-card">
            <div class="proof-ico" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M6 8.5c0-1.66 1.34-3 3-3h6c1.66 0 3 1.34 3 3v7c0 1.66-1.34 3-3 3H9c-1.66 0-3-1.34-3-3v-7Z" stroke="currentColor" stroke-width="1.6"/>
                <path d="M9 11.5h6M9 14.5h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M10 4.5h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="proof-value"><span class="count-up" data-count="120" data-suffix="+">0</span></div>
            <div class="proof-label">High-yield notes</div>
            <div class="proof-sub">Concise topics, dilemmas, and recall cards.</div>
          </article>

          <article class="proof-card">
            <div class="proof-ico" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M4.5 18.5h15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M7.5 16V9.5M12 16V6.5M16.5 16v-4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="proof-value"><span class="count-up" data-count="97" data-suffix="%">0</span></div>
            <div class="proof-label">Focused study time</div>
            <div class="proof-sub">Track completion to stay focused on the full bank.</div>
          </article>

          <article class="proof-card">
            <div class="proof-ico" aria-hidden="true">
              <svg viewBox="0 0 24 24" fill="none">
                <path d="M7.5 10a4.5 4.5 0 0 1 9 0v2.5c0 .55.45 1 1 1h1v5.5c0 .83-.67 1.5-1.5 1.5h-11C4.67 20.5 4 19.83 4 19v-5.5h1c.55 0 1-.45 1-1V10Z" stroke="currentColor" stroke-width="1.6"/>
                <path d="M10.5 14.5v2.5M13.5 14.5v2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="proof-value"><span class="count-up" data-count="10000" data-suffix="+">0</span></div>
            <div class="proof-label">Learners supported</div>
            <div class="proof-sub">Trusted by candidates across the UK.</div>
          </article>
        </div>
      </div>
    </section>

    <section class="guest-steps">
      <div class="container">
        <div class="guest-steps__head">
          <h2 class="guest-h2 guest-h2--left">How it works</h2>
          <p class="guest-steps__sub">A simple path from signup to confident exam-day performance.</p>
        </div>

        <div class="guest-steps__grid">
          <article class="step-card">
            <div class="step-top">
              <div class="step-ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M6 7.5h12M6 12h12M6 16.5h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="step-num">Step 1</div>
            </div>
            <div class="step-title">Pick your plan</div>
            <p class="step-text">Choose a membership that fits your exam timeline.</p>
          </article>

          <article class="step-card step-card--accent">
            <div class="step-top">
              <div class="step-ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M7 7h10v10H7z" stroke="currentColor" stroke-width="1.6"/>
                  <path d="M9 12h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
              </div>
              <div class="step-num">Step 2</div>
            </div>
            <div class="step-title">Track completion</div>
            <p class="step-text">Work through the full question bank, notes, and flashcards.</p>
          </article>

          <article class="step-card">
            <div class="step-top">
              <div class="step-ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                  <path d="M5 14.5l4 4 10-11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="step-num">Step 3</div>
            </div>
            <div class="step-title">Sit mocks and review</div>
            <p class="step-text">Review explanations to close gaps and build confidence.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="guest-reviews">
      <div class="container">
        <h2 class="guest-h2">Ace your exam with Revise MRCEM</h2>

        <div class="review-grid">
          <article class="review-card">
            <div class="review-head">
              <span class="review-avatar">DC</span>
              <div>
                <div class="review-name">Dr Azmain Chowdhury</div>
                <div class="review-meta">Successful MRCEM candidate</div>
              </div>
            </div>

            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              "ReviseMRCEM helped me lock in high-yield topics and stay consistent. The question bank felt closest to the exam."
            </p>
            <a class="review-link" href="#">Read more</a>
          </article>

          <article class="review-card">
            <div class="review-head">
              <span class="review-avatar">DY</span>
              <div>
                <div class="review-name">Dessom Au-Yeung</div>
                <div class="review-meta">University Hospital of Wales</div>
              </div>
            </div>

            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              "The revision notes were concise and practical. It saved me time and improved my confidence."
            </p>
            <a class="review-link" href="#">Read more</a>
          </article>

          <article class="review-card">
            <div class="review-head">
              <span class="review-avatar">LH</span>
              <div>
                <div class="review-name">Lewis Hall</div>
                <div class="review-meta">F2 doctor</div>
              </div>
            </div>

            <div class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            <p class="review-text">
              "Question explanations were clear and helped me learn fast. Mock papers were great for exam stamina."
            </p>
            <a class="review-link" href="#">Read more</a>
          </article>
        </div>
      </div>
    </section>

    <section class="guest-plan">
      <div class="container guest-plan__grid">
        <div class="guest-plan__copy">
          <h2 class="guest-h2 guest-h2--left">Two-plan membership options</h2>
          <p class="guest-plan__lead">
            Pick MRCEM Primary or Intermediate to match your focus on core sciences or
            higher-level emergency and specialty topics.
          </p>
          <ul class="guest-plan__list">
            <li>MRCEM Primary covers core sciences and past recalls</li>
            <li>MRCEM Intermediate focuses on specialty and emergency presentations</li>
            <li>Separate MCQ banks and progress tracking for each exam</li>
            <li>Switch between plans whenever your exam focus changes</li>
          </ul>
        </div>

        <div class="plan-card">
          <span class="plan-tag">Start here</span>
          <div class="plan-title">MRCEM Primary access</div>
          <div class="plan-price">Choose a duration that matches your exam date</div>
          <div class="plan-divider"></div>
          <ul class="plan-list">
            <li>Instant access after checkout</li>
            <li>Primary MCQs, notes, and mock papers</li>
            <li>Track progress across core topics</li>
          </ul>
          <a class="btn-primary" href="{{ route('register') }}">Start revising now</a>
          <div class="plan-trust">Secure checkout and instant access.</div>
        </div>
      </div>
    </section>

    <section class="guest-cta">
      <div class="container guest-cta__inner">
        <h2 class="guest-cta__title">The MRCEM is more competitive than ever...</h2>
        <p class="guest-cta__sub">Secure your dream NHS training job with Revise MRCEM.</p>

        <div class="guest-cta__actions">
          <a class="btn-primary btn-primary--dark" href="{{ route('register') }}">Don't miss out - Join today</a>
          <a class="btn-ghost btn-ghost--dark" href="{{ route('login') }}">Sign in</a>
        </div>
      </div>
    </section>
  </main>
@endsection

@push('scripts')
<script>
  (function () {
    function animateCounter(el) {
      var end = parseInt(el.getAttribute('data-count'), 10) || 0;
      var suffix = el.getAttribute('data-suffix') || '';
      var prefix = el.getAttribute('data-prefix') || '';
      var duration = parseInt(el.getAttribute('data-duration'), 10) || 1200;
      var start = 0;
      var startTime = null;
      var formatter = new Intl.NumberFormat();

      function tick(timestamp) {
        if (!startTime) {
          startTime = timestamp;
        }
        var progress = Math.min((timestamp - startTime) / duration, 1);
        var value = Math.floor(progress * (end - start) + start);
        el.textContent = prefix + formatter.format(value) + suffix;
        if (progress < 1) {
          window.requestAnimationFrame(tick);
        }
      }

      window.requestAnimationFrame(tick);
    }

    function initCounters() {
      var counters = document.querySelectorAll('.count-up');
      if (!counters.length) {
        return;
      }

      if (!('IntersectionObserver' in window)) {
        counters.forEach(animateCounter);
        return;
      }

      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            animateCounter(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.4 });

      counters.forEach(function (counter) {
        observer.observe(counter);
      });
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initCounters);
    } else {
      initCounters();
    }
  })();
</script>
@endpush
