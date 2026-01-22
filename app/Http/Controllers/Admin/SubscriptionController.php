<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $subscriptions = Subscription::with(['user', 'plan', 'coupon'])
            ->latest()
            ->paginate(12);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }
}
