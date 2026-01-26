@extends('layouts.app')

@section('title', 'REVISE MRCEM - Subscription')

@section('content')
  <main class="page reg-page">
    <div class="container reg-wrap">
      <span class="reg-hero-accent">Subscription</span>
      <h1 class="reg-hero-title">Choose your plan</h1>
      <p class="reg-hero-sub">
        Pick the MRCEM Primary or Intermediate plan that matches your exam timeline and unlock access instantly.
      </p>

      <div class="reg-grid">
        <section class="reg-card reg-card--quote">
          <div class="reg-card__title">Everything included</div>
          <ul class="reg-ticks">
            <li>Full question bank with detailed explanations.</li>
            <li>Revision notes, flashcards, and mock papers.</li>
            <li>Completion tracking to guide your revision.</li>
            <li>Instant access after checkout.</li>
          </ul>
        </section>

        <section class="reg-card reg-card--form">
          <div class="reg-form-title">Start your subscription</div>

          @if ($errors->any())
            <div class="qb-card" style="margin-bottom:16px;">
              {{ $errors->first() }}
            </div>
          @endif

          <form action="{{ route('subscribe.checkout') }}" method="post">
            @csrf
            <div class="reg-form-sub">Choose your plan</div>

            @foreach ($plansByExam as $examType => $plans)
              <div class="reg-form-sub" style="margin-top:12px;">
                {{ $examTypes[$examType] ?? 'Exam package' }}
              </div>
              @foreach ($plans as $plan)
                @php
                  $isSelected = old('plan_id')
                    ? (int) old('plan_id') === $plan->id
                    : $plan->id === $defaultPlanId;
                @endphp
                <label class="plan {{ $isSelected ? 'is-selected' : '' }}" data-plan="{{ $plan->display_label }}" data-price="&pound;{{ $plan->price_gbp }}" data-perday="{{ $plan->per_day }}">
                  <input type="radio" name="plan_id" value="{{ $plan->id }}" @checked($isSelected) />
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

            <button class="reg-submit" type="submit">Continue to checkout</button>
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

      if (!plans.length) {
        return;
      }

      var updateSummary = function (plan) {
        if (nameEl && valueEl && metaEl) {
          nameEl.textContent = plan.getAttribute('data-plan') || 'Selected plan';
          valueEl.textContent = plan.getAttribute('data-price') || '';
          metaEl.textContent = (plan.getAttribute('data-perday') || '') + ' - Cancel anytime';
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
        });
      });

      var preselected = document.querySelector('.plan.is-selected');
      if (preselected) {
        updateSummary(preselected);
      }
    })();
  </script>
@endpush
