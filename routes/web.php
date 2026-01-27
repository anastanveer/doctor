<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AttemptController as AdminAttemptController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\MockPaperController as AdminMockPaperController;
use App\Http\Controllers\Admin\MockPaperQuestionController as AdminMockPaperQuestionController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RevisionNoteController as AdminRevisionNoteController;
use App\Http\Controllers\Admin\RevisionTopicController as AdminRevisionTopicController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MockPapersController;
use App\Http\Controllers\MockPaperProgressController;
use App\Http\Controllers\McqAttemptController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\RevisionNotesController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('guest-home');
})->name('home');
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'subscribed']);
Route::get('/question-bank', [PageController::class, 'questionBank'])->name('question-bank')->middleware(['auth', 'subscribed']);
Route::get('/revision-notes', [RevisionNotesController::class, 'index'])->name('revision-notes')->middleware(['auth', 'subscribed']);
Route::get('/revision-notes/{topic:slug}', [RevisionNotesController::class, 'topic'])->name('revision-notes.topic')->middleware(['auth', 'subscribed']);
Route::get('/revision-notes/{topic:slug}/{note:slug}', [RevisionNotesController::class, 'show'])
    ->name('revision-notes.show')
    ->middleware(['auth', 'subscribed'])
    ->scopeBindings();
Route::get('/flashcards', [PageController::class, 'flashcards'])->name('flashcards');
Route::get('/mock-papers', [MockPapersController::class, 'index'])->name('mock-papers')->middleware(['auth', 'subscribed']);
Route::get('/mock-papers/{mockPaper:slug}', [MockPapersController::class, 'show'])->name('mock-papers.show')->middleware(['auth', 'subscribed']);
Route::post('/mock-papers/{mockPaper:slug}/progress', [MockPaperProgressController::class, 'store'])
    ->name('mock-papers.progress')
    ->middleware(['auth', 'subscribed']);
Route::get('/account', [PageController::class, 'account'])->name('account')->middleware(['auth', 'subscribed']);
Route::get('/support', [PageController::class, 'support'])->name('support')->middleware(['auth', 'subscribed']);
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/mcq-session', [PageController::class, 'mcqSession'])->name('mcq-session')->middleware(['auth', 'subscribed']);
Route::post('/mcq-attempts', [McqAttemptController::class, 'store'])->name('mcq-attempts.store')->middleware(['auth', 'subscribed']);
Route::get('/free-msra-resources', [PageController::class, 'freeResources'])->name('free-resources');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/reviews', [PageController::class, 'reviews'])->name('reviews');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordController::class, 'showReset'])->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/subscribe', [BillingController::class, 'showSubscribe'])->name('subscribe')->middleware('auth');
Route::post('/subscribe', [BillingController::class, 'startCheckout'])->name('subscribe.checkout')->middleware('auth');
Route::get('/checkout/success', [BillingController::class, 'success'])->name('checkout.success')->middleware('auth');
Route::get('/checkout/cancel', [BillingController::class, 'cancel'])->name('checkout.cancel')->middleware('auth');
Route::post('/subscription/cancel', [BillingController::class, 'cancelSubscription'])->name('subscription.cancel')->middleware('auth');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('mcq-results', [AdminAttemptController::class, 'index'])->name('attempts.index');
    Route::resource('questions', QuestionController::class)->except(['show']);
    Route::resource('topics', AdminTopicController::class)->except(['show']);
    Route::resource('mock-papers', AdminMockPaperController::class)->except(['show']);
    Route::resource('mock-questions', AdminMockPaperQuestionController::class)->except(['show']);
    Route::resource('revision-topics', AdminRevisionTopicController::class)->except(['show']);
    Route::resource('revision-notes', AdminRevisionNoteController::class)->except(['show']);
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::resource('coupons', CouponController::class)->only(['index', 'store', 'destroy']);
    Route::get('logs', [LogController::class, 'index'])->name('logs');
});
