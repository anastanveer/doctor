@extends('layouts.app')

@section('title', 'REVISE MRCEM • About')

@section('content')
  <main class="page about-page">
    <section class="about-hero">
      <div class="container">
        <h1 class="about-title">About Revise MRCEM</h1>
        <p class="about-sub">
          Built by doctors, for doctors. Every resource is crafted to match the MRCEM exam.
        </p>
      </div>
    </section>

    <section class="about-body">
      <div class="container">
        <div class="about-copy">
          <p>
            Revise MRCEM was created with one clear purpose: to help doctors pass the MRCEM Primary and
            MRCEM Intermediate exams with confidence. Unlike generic question banks, every resource on our platform
            is designed specifically for the MRCEM, mirroring the format, style, and difficulty of the real exam.
          </p>
          <p>
            Our team of contributors includes emergency medicine clinicians and educators, ensuring that every
            question, note, and mock paper reflects the standard you need not just to pass, but to excel.
          </p>
        </div>

        <div class="about-quote">
          <div class="about-quote__title">Here's what last years candidates said:</div>
          <ul class="about-quote-list">
            <li>"Thanks to ReviseMRCEM, I passed MRCEM Primary first time and felt ready for the real exam." – Dr Azmain C.</li>
            <li>"ReviseMRCEM was my go-to question platform. The explanations were exactly what I needed." – Lewis H.</li>
            <li>"It ended up being the only question bank I used for MRCEM Intermediate." – Desson A-Y.</li>
          </ul>
          <a class="about-link" href="#">Read more reviews</a>
        </div>
      </div>
    </section>

    <section class="about-reco">
      <div class="container about-reco__inner">
        <div class="about-reco__title">As Recommended By...</div>
        <div class="about-logos">
          <div class="about-logo-card">
            <span class="about-logo-mark">A</span>
            <span class="about-logo-name">Anaesthesier</span>
          </div>
          <div class="about-logo-card">
            <span class="about-logo-mark">M</span>
            <span class="about-logo-name">Mind the Bleep</span>
          </div>
          <div class="about-logo-card">
            <span class="about-logo-mark">R</span>
            <span class="about-logo-name">Radiology Café</span>
          </div>
          <div class="about-logo-card">
            <span class="about-logo-mark">W</span>
            <span class="about-logo-name">WPWN</span>
          </div>
        </div>
      </div>
    </section>

    <section class="about-features">
      <div class="container">
        <h2 class="about-h2">What you'll find inside</h2>
        <ul class="about-list">
          <li>Quality-assured questions written to match the official MRCEM exam style.</li>
          <li>Comprehensive revision notes covering both Primary and Intermediate topics.</li>
          <li>Separate MCQ banks for core sciences and specialty emergency presentations.</li>
          <li>Mock exams designed to replicate exam conditions and frequently tested topics.</li>
        </ul>
        <p class="about-foot">
          We've helped thousands of doctors maximise their MRCEM scores and secure their first-choice training posts.
          Every membership is backed by our 100% money-back guarantee if you're unsuccessful.
        </p>
      </div>
    </section>

    <section class="about-help">
      <div class="container">
        <h2 class="about-h2">Need help?</h2>
        <p>
          We pride ourselves on excellent customer support and aim to respond to all enquiries within a few hours.
          If we don't reply instantly, it's only because we're helping patients — but we always get back to you quickly.
        </p>
        <a class="about-mail" href="mailto:support@revisemrcem.com">support@revisemrcem.com</a>
      </div>
    </section>
  </main>
@endsection
