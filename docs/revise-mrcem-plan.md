# Revise MRCEM Platform – Delivery Blueprint

This document turns the three requirement screenshots into an execution-ready plan for a Laravel 11 + Livewire 3 platform. It covers UX, modules, database tables, sample data, and the delivery roadmap so we can demo locally with dummy content first and later switch to production APIs and Stripe keys.

---

## 1. Product Overview

| Item | Details |
| --- | --- |
| Project | `revise MRCEM` – premium medical exam question bank & analytics |
| Primary Goal | Unique (non-copied) dashboard-first experience inspired by revisemrcem.com flow but redesigned with modern UI, cards, analytics blocks, charts, and responsive layouts |
| Target Stack | Laravel 11, Livewire 3, Blade, MySQL 8, Tailwind CSS + Bootstrap utilities, Chart.js, Stripe SDK, Laravel Scheduler/Cron, Queues for mails |
| Hosting | GoDaddy/Hostinger VPS (per client note); deploy with Git + Envoy or Forge later |
| Deliverables | Web app (user + admin/editor dashboards) + database migrations + seeders/dummy data + deployment notes |

---

## 2. User Roles & Permissions

| Role | Capabilities |
| --- | --- |
| Admin | Full CRUD on users, categories, questions, notes, flashcards; bulk CSV uploads; exam management; analytics dashboards; can block/delete users; screenshot alert console; configure subscription plans; manual subscription extension; system settings (.env, Stripe keys) |
| Editor (2–3) | CRUD on assigned categories/questions/notes/flashcards; limited analytics for their content; cannot change billing/security settings |
| User | Access purchased plans, attempt practice mode (random/no timer), attempt mock exams (timed, pause/resume), review notes/flashcards, see analytics, manage profile, reset password via email |

Password reset flows use Laravel Fortify/Jetstream style tokens and server-side rate limiting.

---

## 3. Feature Modules (Top Nav Concept)

1. **Question Bank** – practice mode (randomized, untimed) with 5 options/question, single correct answer, explanation, supporting diagrams/images.
2. **Revision Notes** – CRUD by admin/editor; hierarchical categories/sub-categories.
3. **Flashcards** – CRUD with spaced repetition scheduling; later upgrade to push reminders via email.
4. **Mock Papers** – timed exams only; pause/resume; retake; instant results with review screen.
5. **Analytics Dashboard** – cards for average score, percentile summary, time usage, weak topics, streaks; progress chart by topic.
6. **Subscriptions** – plans (1/3/6 months) with Stripe checkout, auto-lock after expiry, renewal reminder email one week before expiry, demo access (10 questions).
7. **Security Panel** – screenshot detection alerts, visibility change/paste events, auto block after >2 logged attempts, manual block/unblock.
8. **Admin Ops** – bulk upload (CSV/XLSX), exam management, user management, seed/demo data toggles.

---

## 4. UX / UI Strategy

- **Layout**: Dashboard-first design with sticky top nav, collapsible sidebar, metric cards, glassmorphism gradients for hero, and chart widgets (line chart for progress, donut for topic performance, stacked bars for attempts).
- **Color direction**: Midnight blue background gradients + neon cyan/amber accents for CTA buttons; stays distinct from reference site.
- **Interactions**: Livewire for asynchronous filters, attempt pagination, and inline editing; Alpine.js for quick toggles; Chart.js dynamic updates.
- **Responsive**: Breakpoints for desktop (>1280px), tablet (768–1279px), mobile (<767px) with card stacking and bottom nav quick actions on mobile.
- **Accessibility**: Minimum 4.5:1 contrast, keyboard navigation, ARIA labels on timers/charts.
- **Demo Mode**: `.env` flag `DEMO_MODE=true` hides Stripe live keys, uses seeded dummy data, and displays message “Demo data loaded”.

---

## 5. Application Flow

### Sign Up / Subscription
1. User registers → email verification.
2. Picks plan (1/3/6 months) → Stripe checkout (test keys initially).
3. On success, webhook marks subscription active, grants access within 30 minutes (per spec) and triggers welcome email.
4. Scheduler cron sends reminder 7 days before expiry with extend link; auto locks exactly at expiry.

### Exam Modes
- **Practice**: choose categories, number of questions; no strict timer; autosave progress; can pause/resume unlimited.
- **Mock (Timed)**: pick available mock paper; timer + optional pause/resume; store attempts + results; show explanations after submission.
- **Retake**: unlimited retakes; analytics records each attempt.

### Analytics
- Score summary, percentile vs population (calculated from aggregated attempts), topic performance, progress by subtopic, fastest/slowest questions, overall accuracy.
- Optional printable PDF summary.

### Security
- JavaScript listeners detect `visibilitychange`, `PrintScreen` key (best effort), resizing; log events.
- After 2 screenshot events, auto flag attempt and optionally auto submit.
- Admin can block/unblock flagged accounts; email notification triggered.

---

## 6. Database Schema (MySQL)

| Table | Key Columns | Notes |
| --- | --- | --- |
| `users` | `id`, `name`, `email`, `password`, `role_id`, `status`, `email_verified_at`, `blocked_at` | Soft deletes; `status` = active/suspended/demo |
| `roles` | `id`, `name` (`admin`, `editor`, `user`), `permissions (json)` | Pre-seeded |
| `subscription_plans` | `id`, `name`, `duration_days` (30/90/180), `price_cents`, `demo_questions_limit` | Manageable through admin |
| `subscriptions` | `id`, `user_id`, `plan_id`, `stripe_id`, `starts_at`, `expires_at`, `status`, `auto_lock_at` | Auto-lock column for clarity |
| `categories` | `id`, `title`, `slug`, `description`, `sort_order` | 3 main categories |
| `subcategories` | `id`, `category_id`, `title`, `slug`, `description` | ~15 entries |
| `questions` | `id`, `subcategory_id`, `title`, `body`, `diagram_path`, `explanation`, `difficulty`, `status`, `created_by` | Body stores HTML/markdown |
| `question_options` | `id`, `question_id`, `option_label`, `option_text`, `is_correct` | 5 options per question |
| `mock_papers` | `id`, `title`, `duration_minutes`, `total_questions`, `instructions`, `status` | Timed exams only |
| `mock_paper_questions` | `id`, `mock_paper_id`, `question_id`, `sort_order` | Many-to-many |
| `attempts` | `id`, `user_id`, `mode` (`practice`, `mock`), `mock_paper_id`, `started_at`, `submitted_at`, `time_remaining`, `score`, `percentile` | Stores meta analytics |
| `attempt_answers` | `id`, `attempt_id`, `question_id`, `option_id`, `is_correct`, `time_spent` | For review |
| `revision_notes` | `id`, `category_id`, `title`, `content`, `tags (json)`, `published_at` | Manageable by admin/editor |
| `flashcards` | `id`, `subcategory_id`, `front`, `back`, `hint`, `spaced_review_at` | |
| `bulk_uploads` | `id`, `user_id`, `file_path`, `type`, `status`, `summary_json` | Track CSV imports |
| `screenshot_logs` | `id`, `user_id`, `attempt_id`, `event_type`, `detected_at`, `metadata (json)` | For audit |
| `email_notifications` | `id`, `user_id`, `type`, `sent_at`, `status`, `payload (json)` | Cron job record |

### Relationships Diagram (textual)
`users` 1-* `subscriptions`; `subscriptions` *-1 `subscription_plans`; `categories` 1-* `subcategories`; `subcategories` 1-* `questions`; `questions` 1-* `question_options`; `questions` *-* `mock_papers` via `mock_paper_questions`; `attempts` links to `users` + optional `mock_papers`; `attempt_answers` 1-* `questions`.

---

## 7. Dummy Data Snapshot

| Table | Sample Rows (id → data) |
| --- | --- |
| `subscription_plans` | 1→`Starter`, 30 days, `$39`; 2→`Focused`, 90 days, `$99`; 3→`Intense`, 180 days, `$159`; demo limit = 10 questions |
| `categories` | 1→`Clinical Scenarios`, 2→`Procedures`, 3→`Pharmacology` |
| `subcategories` | 1→1:`Acute Medicine`; 2→1:`Trauma`; ... approx 15 entries |
| `questions` | 1001: `Septic Shock Management` (diagram). Explanation text referencing NICE guidelines. Difficulty `medium`. `created_by` = admin ID 1. |
| `question_options` | 1001A→`Administer 30 ml/kg fluids` (correct); B→`Delay fluids`; etc |
| `mock_papers` | `Mock Paper 01 – Trauma`, 60 minutes, 50 questions |
| `revision_notes` | `ALS Algorithm 2024 Update` etc |
| `flashcards` | `Front: Causes of metabolic acidosis`, `Back: MUDPILES mnemonic`, `spaced_review_at` future date |

Seeders should insert 30+ questions, 2 mock papers, 10 flashcards, and 5 notes for demo.

---

## 8. Laravel Build Plan

| Phase | Tasks | Tools/Files |
| --- | --- | --- |
| 1. Scaffold | Install Laravel 11 (`composer create-project`), configure `.env`, auth scaffolding (`php artisan make:auth` via Breeze or Jetstream with Livewire). | `config/app.php`, `.env.example` updates |
| 2. Database | Create migrations for tables above (`php artisan make:migration create_questions_table`). Use enums for `mode`, `status`. Add seeders/factories for dummy data (`DatabaseSeeder`). | `database/migrations/*`, `database/seeders/*` |
| 3. Modules | Build Livewire components: `QuestionBank`, `PracticeAttempt`, `MockAttempt`, `AnalyticsDashboard`, `RevisionNotes`, `Flashcards`, `SubscriptionCheckout`, `ScreenshotWatcher`. | `app/Livewire/*`, `resources/views/livewire/*` |
| 4. Admin Panel | Use Filament or custom Livewire admin layout; include bulk upload (import via `maatwebsite/excel`), user management, alerts center. | `routes/admin.php`, `app/Http/Controllers/Admin/*` |
| 5. Stripe Integration | Use `laravel/cashier` for subscriptions; configure webhooks and scheduler for reminders. | `routes/web.php`, `app/Listeners/StripeWebhookHandler.php` |
| 6. Security | JavaScript snippet to detect `PrintScreen`/`visibilitychange`; Livewire event to backend `ScreenshotEventController`. Auto-block logic in `App\Actions\BlockUserAfterScreenshots`. | `resources/js/security.js`, `routes/api.php` |
| 7. Analytics & Charts | Aggregation queries using `attempts` table; feed Chart.js via Livewire JSON props. | `app/Services/AnalyticsService.php` |
| 8. Testing & Deployment | Feature tests for attempt flow and subscription locking; schedule `php artisan schedule:run`; deployment script for GoDaddy/Hostinger. | `tests/Feature/*`, `deploy/README.md` |

---

## 9. API & Routes Snapshot

| Route | Purpose | Middleware |
| --- | --- | --- |
| `GET /` | Landing page (hero + CTA + modules preview) | `guest` |
| `GET /dashboard` | Unified dashboard (cards, charts, quick actions). | `auth`, `verified`, `subscribed` |
| `GET /practice` (Livewire) | Start/resume practice attempt. | `auth`, `subscribed` |
| `POST /practice/save` | Autosave progress. | `auth` |
| `GET /mock-papers` | List timed mock exams. | `auth`, `subscribed` |
| `POST /mock-papers/{paper}/start` | Creates attempt; returns Livewire component state. | `auth` |
| `GET /notes` / `GET /flashcards` | Study sections. | `auth` (demo allowed limited) |
| `GET /admin/*` | All admin modules (bulk upload, analytics, subscriptions). | `auth`, `role:admin` |
| `POST /webhooks/stripe` | Payment updates. | none (signed) |
| `POST /security/screenshot` | Log front-end screenshot detection. | `auth` |

---

## 10. Email & Notification Templates

1. **Welcome Email** – plan name, expiry date, quick links.
2. **Reminder Email** – 7-day reminder with extend CTA.
3. **Screenshot Alert** – notify admin when user flagged.
4. **Password Reset** – default Laravel template.
5. **Demo Upsell** – after user consumes 10 questions without subscription.

Use Markdown mailables + queue for reliability.

---

## 11. Deployment Checklist

- [ ] `.env` configured (APP_KEY, DB creds, Stripe keys, MAILER, DEMO_MODE flag).
- [ ] `php artisan migrate --seed` with dummy dataset before recording demo video.
- [ ] `php artisan storage:link` for diagram uploads.
- [ ] Scheduler + Queue worker running (Supervisor config for Hostinger/GoDaddy).
- [ ] Stripe webhook endpoint created & stored.
- [ ] Cloudflare/GoDaddy SSL ready.
- [ ] Daily DB backups (mysqldump cron) stored securely.

---

## 12. Next Actions

1. Confirm color palette & typography mood-board.
2. Approve data model/migrations list.
3. Start Laravel scaffolding + dummy data seeding locally.
4. Iterate on dashboard UI using Tailwind/TW plugins + Chart.js.
5. Record short Loom demo for client once dummy content + flows ready.

With this blueprint we can immediately start building the Laravel project while ensuring every bullet from the screenshots is covered. Let me know if you need additional wireframes or Figma references. 

