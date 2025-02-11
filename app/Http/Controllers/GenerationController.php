<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Support\Facades\Auth;

class GenerationController extends Controller
{
    public function index()
    {
        $generations = Generation::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('generations.index', [
            'generations' => $generations
        ]);
    }
}
