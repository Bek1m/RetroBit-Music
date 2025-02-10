<?php

namespace App\Http\Controllers;

use App\Models\CreditPackage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        // Get credit packages from database
        $creditPackages = CreditPackage::where('is_active', true)->get();

        // Get user's transactions
        $billingHistory = Transaction::where('user_id', Auth::id())
            ->latest()
            ->get();

        // Get user's subscription info (for now using a basic structure)
        $subscription = (object)[
            'name' => 'Free Plan',
            'description' => 'Basic features included',
            'generations_per_month' => 10,
            'max_duration' => 60,
            'next_billing_date' => 'N/A'
        ];

        return view('billing.index', compact('subscription', 'creditPackages', 'billingHistory'));
    }
}
