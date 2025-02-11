<?php

namespace App\Http\Controllers;

use App\Models\UserCredit;
use App\Models\CreditPackage;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    // CreditController.php
    public function index()
    {
        $userCredits = UserCredit::firstOrCreate(
            ['user_id' => Auth::id()],
            ['credits_balance' => 0, 'lifetime_credits' => 0]
        );

        $creditPackages = CreditPackage::where('is_active', true)->get();

        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('credits.index', [
            'userCredits' => $userCredits,
            'creditPackages' => $creditPackages,
            'transactions' => $transactions
        ]);
    }

    public function addCredits()
    {
        try {
            DB::beginTransaction(); // Start transaction to ensure data consistency

            // Get or create user credits record
            $userCredits = UserCredit::firstOrCreate(
                ['user_id' => Auth::id()],
                ['credits_balance' => 0, 'lifetime_credits' => 0]
            );

            $creditsToAdd = 10;
            $oldBalance = $userCredits->credits_balance; // Save old balance for verification

            // Update user credits - using direct update instead of increment
            $userCredits->credits_balance = $oldBalance + $creditsToAdd;
            $userCredits->lifetime_credits += $creditsToAdd;
            $userCredits->save();

            // Verify the update
            $userCredits->refresh(); // Refresh from database

            // Create transaction record
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'credit_package_id' => null,
                'transaction_type' => 'test_credit',
                'amount' => $creditsToAdd,
                'status' => 'completed',
                'payment_id' => 'TEST_' . uniqid()
            ]);

            DB::commit(); // Commit transaction if everything succeeded

            return response()->json([
                'success' => true,
                'message' => 'Credits added successfully',
                'old_balance' => $oldBalance,
                'new_balance' => $userCredits->credits_balance,
                'transaction_id' => $transaction->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if anything failed
            return response()->json([
                'success' => false,
                'message' => 'Failed to add credits: ' . $e->getMessage()
            ], 500);
        }
    }
}
