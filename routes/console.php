<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\SubscriptionReminderService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subscriptions:send-expiry-reminders {--days=7}', function (SubscriptionReminderService $reminders) {
    $days = (int) $this->option('days');
    $count = $reminders->sendExpiryReminders($days > 0 ? $days : 7);
    $this->info("Expiry reminders sent: {$count}");
})->purpose('Send subscription expiry reminder emails');
