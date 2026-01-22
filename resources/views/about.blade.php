@extends('layouts.app')

@section('title', 'REVISE MSRA • About')

@section('content')
  <main class="page about-page">
    <section class="about-hero">
      <div class="container">
        <h1 class="about-title">About Revise MSRA</h1>
        <p class="about-sub">
          Built by doctors, for doctors. Every resource is crafted to match the MSRA exam.
        </p>
      </div>
    </section>

    <section class="about-body">
      <div class="container">
        <div class="about-copy">
          <p>
            Revise MSRA was created with one clear purpose: to help doctors achieve top scores in the Multi-Specialty
            Recruitment Assessment. Unlike generic question banks, every resource on our platform is designed
            specifically for the MSRA, mirroring the format, style, and difficulty of the real exam.
          </p>
          <p>
            Our team of contributors includes doctors who scored in the <strong>top 1% of candidates</strong>
            (over 600 marks), ensuring that every question, note, and mock paper reflects the standard you need
            not just to pass, but to excel.
          </p>
        </div>

        <div class="about-quote">
          <div class="about-quote__title">Here's what last years candidates said:</div>
          <ul class="about-quote-list">
            <li>"Thanks to ReviseMSRA, I got my top choice GP job, scoring 623 in the exam and ranking in the top 0.1%." – Dr Azmain C.</li>
            <li>"ReviseMSRA was my go-to question platform. I scored 602 securing a CST interview and my top choice ACCF post." – Lewis H.</li>
            <li>"It ended up being the only question bank I used – I scored 606 and secured an anaesthetics training post straight after F2." – Desson A-Y.</li>
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
          <li>Over 3,000 quality-assured questions written to match the official exam style.</li>
          <li>Comprehensive revision notes covering the full MSRA curriculum, with key learning points and exam clues.</li>
          <li>Professional Dilemmas (SJT) bank with 250+ realistic scenarios and explanations.</li>
          <li>Mock exams designed to replicate exam conditions and frequently tested topics.</li>
        </ul>
        <p class="about-foot">
          We've helped thousands of doctors maximise their MSRA scores and secure their first-choice training posts.
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
        <a class="about-mail" href="mailto:support@revisemsra.com">support@revisemsra.com</a>
      </div>
    </section>
  </main>
@endsection
