@extends('layouts.app')

@section('title', 'REVISE MSRA • Your account')

@section('content')
  <main class="page">
    <div class="account-wrap">
      <div class="account-head">
        <h1 class="account-title">Your account</h1>
        <div class="account-actions">
          <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="btn-signout" type="submit">
              <span class="btn-ico">↗</span>
              Sign out
            </button>
          </form>
        </div>
      </div>

      <div class="account-tabs" role="tablist" aria-label="Account tabs">
        <button class="account-tab is-active" type="button" data-tab="profile" role="tab" aria-selected="true">
          Profile
        </button>
        <button class="account-tab" type="button" data-tab="login" role="tab" aria-selected="false">
          Login details
        </button>
        <button class="account-tab" type="button" data-tab="membership" role="tab" aria-selected="false">
          Membership
        </button>
      </div>

      <section class="account-card" data-panel="profile" role="tabpanel">
        <form class="account-form">
          <div class="form-row">
            <label for="firstName">First name</label>
            <input id="firstName" type="text" value="{{ auth()->user()->name }}" />
          </div>

          <div class="form-row">
            <label for="lastName">Last name</label>
            <input id="lastName" type="text" value="" />
          </div>

          <div class="form-row">
            <label for="displayName">Display name</label>
            <div class="form-help">This is what is displayed throughout the web application</div>
            <input id="displayName" type="text" value="{{ auth()->user()->name }}" />
          </div>

          <div class="form-row">
            <label for="examDate">Date of your MSRA exam</label>
            <div class="form-help">If entered, a countdown is displayed on your dashboard</div>
            <input id="examDate" type="date" value="2026-02-20" />
          </div>

          <div class="form-row">
            <label for="photo">Profile photo</label>
            <div class="form-help">Minimum size 160 x 160 pixels</div>
            <input id="photo" type="file" />
          </div>

          <button class="btn-primary" type="button">Save profile</button>
        </form>
      </section>

      <section class="account-card" data-panel="login" role="tabpanel" style="display:none;">
        <form class="account-form">
          <div class="form-row">
            <label for="email">Email</label>
            <div class="form-help">This is used to login</div>
            <input id="email" type="email" value="{{ auth()->user()->email }}" />
          </div>

          <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <button class="btn-primary" type="button">Save email</button>
            <a class="btn-ghost" href="{{ route('password.request') }}">Reset password</a>
          </div>
        </form>
      </section>

      <section class="account-card" data-panel="membership" role="tabpanel" style="display:none;">
        @if (session('status'))
          <div class="membership-note" style="margin-bottom:14px;">
            {{ session('status') }}
          </div>
        @endif

        <div class="sub-grid">
          <div class="sub-card">
            <div class="sub-card__head">
              <h3>Current subscription</h3>
              <span class="sub-status {{ $activeSubscription ? 'is-active' : 'is-none' }}">
                {{ $activeSubscription ? ucfirst($activeSubscription->status) : 'No active plan' }}
              </span>
            </div>

            @if ($activeSubscription)
              <div class="sub-row">
                <span>Plan</span>
                <strong>{{ $activeSubscription->plan->name }}</strong>
              </div>
              <div class="sub-row">
                <span>Start date</span>
                <strong>{{ $activeSubscription->started_at->format('d M Y') }}</strong>
              </div>
              <div class="sub-row">
                <span>Expiry date</span>
                <strong>{{ $activeSubscription->expires_at->format('d M Y') }}</strong>
              </div>
              <div class="sub-row">
                <span>Auto renew</span>
                <strong>{{ $activeSubscription->auto_renew ? 'On' : 'Off' }}</strong>
              </div>
            @else
              <p class="sub-empty">No active subscription. Choose a plan to unlock your dashboard.</p>
            @endif

            <form class="sub-plan-form" method="post" action="{{ route('subscribe.checkout') }}">
              @csrf
              <input type="hidden" name="terms" value="1" />
              <div class="sub-plan-form__row">
                <div>
                  <label class="sub-plan-label" for="plan-change">Change plan</label>
                  <select id="plan-change" name="plan_id" class="sub-plan-select">
                    @foreach ($plans as $plan)
                      <option value="{{ $plan->id }}" @selected($activeSubscription?->plan_id === $plan->id)>
                        {{ $plan->label }} - &pound;{{ $plan->price_gbp }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <button class="btn-primary-dark" type="submit">
                  {{ $activeSubscription ? 'Update plan' : 'Start subscription' }}
                </button>
              </div>
              <div class="sub-plan-help">
                Plan updates start immediately after checkout.
              </div>
            </form>

            <div class="sub-actions">
              @if ($activeSubscription && $activeSubscription->status === 'active')
                <form method="post" action="{{ route('subscription.cancel') }}">
                  @csrf
                  <button class="btn-outline" type="submit">Cancel subscription</button>
                </form>
              @endif
            </div>
            <div class="sub-foot">Access remains until expiry after cancellation.</div>
          </div>

          <div class="sub-card sub-card--history">
            <h3>Subscription history</h3>
            <div class="sub-history">
              @forelse ($subscriptions as $subscription)
                <div class="sub-history__item">
                  <div>
                    <div class="sub-history__title">{{ $subscription->plan->name }}</div>
                    <div class="sub-history__meta">
                      {{ $subscription->started_at->format('d M Y') }} - {{ $subscription->expires_at->format('d M Y') }}
                    </div>
                  </div>
                  <span class="sub-history__status">{{ ucfirst($subscription->status) }}</span>
                </div>
              @empty
                <div class="sub-empty">No subscriptions yet.</div>
              @endforelse
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
@endsection

@push('scripts')
  <script>
    const tabs = document.querySelectorAll('.account-tab');
    const panels = document.querySelectorAll('[data-panel]');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const target = tab.getAttribute('data-tab');

        tabs.forEach(t => {
          t.classList.remove('is-active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('is-active');
        tab.setAttribute('aria-selected', 'true');

        panels.forEach(p => {
          p.style.display = (p.getAttribute('data-panel') === target) ? '' : 'none';
        });
      });
    });
  </script>
@endpush
