<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\UserCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request\ProfileUpdateRequest;
use App\Models\LoginHistory;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        $totalGenerations = Generation::where('user_id', $user->id)->count();

        $userCredits = UserCredit::firstOrCreate(
            ['user_id' => $user->id],
            ['credits_balance' => 0, 'lifetime_credits' => 0]
        );

        // Get recent generations and login history
        $recentActivity = collect();

        // Add login history
        // You'll need to create a login_history table and track logins
        $loginHistory = LoginHistory::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMonth())
            ->latest()
            ->get()
            ->map(function($login) {
                return (object)[
                    'title' => 'Login from ' . $login->ip_address,
                    'type' => 'Login',
                    'created_at' => $login->created_at
                ];
            });

        // Add generations
        $generations = Generation::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($generation) {
                return (object)[
                    'title' => $generation->title,
                    'type' => 'Generation',
                    'created_at' => $generation->created_at
                ];
            });

        // Merge and sort activities
        $recentActivity = $loginHistory->concat($generations)
            ->sortByDesc('created_at')
            ->take(5);

        return view('profile.show', [
            'user' => $user,
            'totalGenerations' => $totalGenerations,
            'creditsRemaining' => $userCredits->credits_balance,
            'downloadsCount' => 0,
            'recentActivity' => $recentActivity
        ]);
    }

    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
