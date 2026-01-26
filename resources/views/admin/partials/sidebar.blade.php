<aside class="admin-sidebar">
  <div class="admin-brand">
    <span class="brand-mark">R</span>
    <div>
      <div class="admin-brand__title">REVISE<span>MRCEM</span></div>
      <div class="admin-brand__tag">Admin</div>
    </div>
  </div>

  <nav class="admin-nav">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M4 4h7v7H4V4Zm9 0h7v5h-7V4ZM4 13h7v7H4v-7Zm9 7v-9h7v9h-7Z" stroke="currentColor" stroke-width="1.6"/>
          </svg>
        </span>
        <span class="admin-nav__text">Dashboard</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.questions.index') }}" class="{{ request()->routeIs('admin.questions.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 6.5h12M6 12h12M6 17.5h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M4.5 6.5h.01M4.5 12h.01M4.5 17.5h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Question bank</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.topics.index') }}" class="{{ request()->routeIs('admin.topics.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 6.5h12M6 12h12M6 17.5h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <circle cx="17.5" cy="17.5" r="2" stroke="currentColor" stroke-width="1.6"/>
          </svg>
        </span>
        <span class="admin-nav__text">Question topics</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.mock-papers.index') }}" class="{{ request()->routeIs('admin.mock-papers.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 5h8l4 4v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M14 5v4h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Mock papers</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.mock-questions.index') }}" class="{{ request()->routeIs('admin.mock-questions.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M7 6.5h10M7 11.5h10M7 16.5h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M4.5 6.5h.01M4.5 11.5h.01M4.5 16.5h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Mock MCQs</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.revision-topics.index') }}" class="{{ request()->routeIs('admin.revision-topics.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M4.5 6.5h6.5a3 3 0 0 1 3 3v9H7.5a3 3 0 0 0-3 3V6.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M13.5 6.5h4a2 2 0 0 1 2 2V20a2 2 0 0 0-2-2h-4V6.5Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Revision topics</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.revision-notes.index') }}" class="{{ request()->routeIs('admin.revision-notes.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M7 5h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8.5 9h6M8.5 12h6M8.5 15h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Revision notes</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M7.5 8.5a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0Z" stroke="currentColor" stroke-width="1.6"/>
            <path d="M4 20c1.6-3 4.2-4.5 8-4.5s6.4 1.5 8 4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Users</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.subscriptions.index') }}" class="{{ request()->routeIs('admin.subscriptions.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <rect x="4" y="6.5" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M4 10h16" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8 14.5h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">Subscriptions</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.attempts.index') }}" class="{{ request()->routeIs('admin.attempts.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M5 4h14v16H5V4Z" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8 8h8M8 12h8M8 16h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="admin-nav__text">MCQ results</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
    <a href="{{ route('admin.coupons.index') }}" class="{{ request()->routeIs('admin.coupons.*') ? 'is-active' : '' }}">
      <span class="admin-nav__left">
        <span class="admin-nav__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none">
            <path d="M6 8h8l4 4-8 8-4-4V8Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
            <circle cx="10" cy="10" r="1.5" fill="currentColor"/>
          </svg>
        </span>
        <span class="admin-nav__text">Coupons</span>
      </span>
      <span class="admin-nav__chev" aria-hidden="true">›</span>
    </a>
  </nav>

  <div class="admin-sidebar__foot">
    <a class="admin-pill" href="{{ route('admin.logs') }}">Error logs</a>
  </div>
</aside>
