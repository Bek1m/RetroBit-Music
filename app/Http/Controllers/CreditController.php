<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    public function index()
    {
        $userCredits = UserCredit::firstOrCreate(
            ['user_id' => Auth::id()],
            ['credits_balance' => 0, 'lifetime_credits' => 0]
        );

        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('credits.index', [
            'userCredits' => $userCredits,
            'transactions' => $transactions
        ]);
    }
}
