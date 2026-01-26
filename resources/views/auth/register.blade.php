@extends('layouts.app')

@section('title', 'REVISE MRCEM - Create account')

@section('content')
  <main class="page reg-page">
    <div class="container reg-wrap">
      <span class="reg-hero-accent">Membership</span>
      <h1 class="reg-hero-title">Create your MRCEM account in minutes</h1>
      <p class="reg-hero-sub">
        Join thousands of candidates using focused, high-yield content to secure top scores.
      </p>
      <div class="reg-trust">
        <span class="reg-trust-pill"><span class="reg-trust-ico">✓</span> Secure checkout</span>
        <span class="reg-trust-pill"><span class="reg-trust-ico">✓</span> Instant access</span>
        <span class="reg-trust-pill"><span class="reg-trust-ico">✓</span> Cancel anytime</span>
      </div>

      <div class="reg-grid">
        <section class="reg-card reg-card--quote">
          <div class="reg-card__title">Why candidates choose Revise MRCEM</div>

          <div class="quote">
            <p>"I started with the question bank and quickly found my weak topics. The notes and mocks did the rest."</p>
            <div class="quote__meta">Dr. Sara A. • Emergency Medicine Training</div>
          </div>

          <div class="reg-section">
            <h2 class="reg-h2">What you get</h2>
            <ul class="reg-ticks">
              <li>Complete MRCEM curriculum coverage with clear explanations.</li>
              <li>Exam-style mock papers to build stamina and timing.</li>
              <li>Revision notes and flashcards for fast recall.</li>
              <li>Completion tracking to focus your revision.</li>
            </ul>
          </div>

          <div class="reg-section">
            <h2 class="reg-h2">Bonus access</h2>
            <ul class="reg-bonuses">
              <li><span class="bonus-ico">✓</span> Primary and Intermediate pathway guidance</li>
              <li><span class="bonus-ico">✓</span> Completion tracking (done / total)</li>
              <li><span class="bonus-ico">✓</span> Instant access after checkout</li>
            </ul>
          </div>

          <div class="reg-section" id="reg-proof">
            <h2 class="reg-h2">4,000+ reasons to join</h2>
            <div class="reg-proof-grid">
              <article class="reg-proof-card">
                <div class="reg-proof-num">4,000+</div>
                <div class="reg-proof-title">Active recall flashcards</div>
                <div class="reg-proof-sub">High-yield concepts distilled for rapid memory.</div>
              </article>
              <article class="reg-proof-card">
                <div class="reg-proof-num">3,000+</div>
                <div class="reg-proof-title">MRCEM-style questions</div>
                <div class="reg-proof-sub">Exam-accurate format with detailed explanations.</div>
              </article>
              <article class="reg-proof-card">
                <div class="reg-proof-num">100%</div>
                <div class="reg-proof-title">All-in-one access</div>
                <div class="reg-proof-sub">Notes, mocks, and completion tracking included.</div>
              </article>
            </div>
          </div>
        </section>

        <section class="reg-card reg-card--form">
          <div class="reg-form-title">Start your subscription</div>

          @if ($errors->any())
            <div class="qb-card" style="margin-bottom:16px;">
              {{ $errors->first() }}
            </div>
          @endif

          <form action="{{ route('register.submit') }}" method="post">
            @csrf
            <div class="reg-field">
              <label for="full-name">Full name</label>
              <input id="full-name" name="name" type="text" value="{{ old('name') }}" placeholder="Muhammad Tayyab" required />
            </div>

            <div class="reg-field">
              <label for="email">Email</label>
              <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="you@example.com" required />
            </div>

            <div class="reg-field">
              <label for="password">Password</label>
              <input id="password" name="password" type="password" placeholder="********" required />
            </div>

            <div class="reg-field">
              <label for="password-confirmation">Confirm password</label>
              <input id="password-confirmation" name="password_confirmation" type="password" placeholder="********" required />
            </div>

            <div class="reg-divider"></div>
            <div class="reg-form-sub">Choose your plan</div>

            @foreach ($plansByExam as $examType => $plans)
              <div class="reg-form-sub" style="margin-top:12px;">
                {{ $examTypes[$examType] ?? 'Exam package' }}
              </div>
              @php
                $lockIntermediate = $examType === 'intermediate' && !$canAccessIntermediate;
              @endphp
              @if ($lockIntermediate)
                <div class="plan-lock">Complete all MRCEM Primary MCQs to unlock Intermediate access.</div>
              @endif
              @foreach ($plans as $plan)
                @php
                  $isSelected = old('plan_id')
                    ? (int) old('plan_id') === $plan->id
                    : $plan->id === $defaultPlanId;
                  $isDisabled = $lockIntermediate;
                @endphp
                <label class="plan {{ $isSelected ? 'is-selected' : '' }} {{ $isDisabled ? 'is-disabled' : '' }}" data-plan="{{ $plan->display_label }}" data-price="&pound;{{ $plan->price_gbp }}" data-perday="{{ $plan->per_day }}">
                  <input type="radio" name="plan_id" value="{{ $plan->id }}" @checked($isSelected) @disabled($isDisabled) />
                  <div class="plan__text">
                    {{ $plan->label }}
                    <span class="plan__price">&pound;{{ $plan->price_gbp }}</span>
                    <span class="plan__hint">Access for {{ $plan->duration_months }} month{{ $plan->duration_months > 1 ? 's' : '' }}</span>
                  </div>
                </label>
              @endforeach
            @endforeach

            @php
              $flatPlans = $plansByExam->flatten();
              $defaultPlan = $flatPlans->firstWhere('id', $defaultPlanId) ?? $flatPlans->first();
            @endphp
            <div class="reg-price" aria-live="polite">
              <div class="reg-price__label">Plan summary</div>
              <div class="reg-price__row">
                <span class="reg-price__name">{{ $defaultPlan?->display_label ?? 'Selected plan' }}</span>
                <span class="reg-price__value">&pound;{{ $defaultPlan?->price_gbp ?? '0.00' }}</span>
              </div>
              <div class="reg-price__meta">{{ $defaultPlan?->per_day ?? '' }} • Cancel anytime</div>
            </div>

            <div class="reg-check">
              <label class="checkline">
                <input type="checkbox" name="terms" value="1" checked />
                I agree to the <a href="{{ route('terms') }}">terms</a> and <a href="{{ route('privacy') }}">privacy policy</a>.
              </label>
            </div>

            <button class="reg-submit" type="submit">Create account</button>

            <div class="reg-small">
              Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    (function () {
      var plans = document.querySelectorAll('.plan');
      var nameEl = document.querySelector('.reg-price__name');
      var valueEl = document.querySelector('.reg-price__value');
      var metaEl = document.querySelector('.reg-price__meta');
          var scrollTarget = document.getElementById('reg-proof');

      if (!plans.length) {
        return;
      }

      var updateSummary = function (plan) {
        if (nameEl && valueEl && metaEl) {
          nameEl.textContent = plan.getAttribute('data-plan') || 'Selected plan';
          valueEl.textContent = plan.getAttribute('data-price') || '';
          metaEl.textContent = (plan.getAttribute('data-perday') || '') + ' • Cancel anytime';
        }
      };

      plans.forEach(function (plan) {
        var input = plan.querySelector('input');
        if (!input) {
          return;
        }
        input.addEventListener('change', function () {
          plans.forEach(function (item) { item.classList.remove('is-selected'); });
          plan.classList.add('is-selected');

          updateSummary(plan);

          if (scrollTarget) {
            scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      var preselected = document.querySelector('.plan.is-selected');
      if (preselected) {
        updateSummary(preselected);
      }
    })();
  </script>
@endpush
