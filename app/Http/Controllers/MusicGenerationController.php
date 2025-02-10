<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\UserCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MusicGenerationController extends Controller
{
    public function generate(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'valence' => 'required|numeric|between:-1,1',
            'arousal' => 'required|numeric|between:-1,1',
            'tempo' => 'required|integer|between:60,180',
            'soundfont' => 'required|in:contra,nintendo,violin',
            'duration' => 'required|integer|in:30,60,120',
            'generation_length' => 'required|integer|min:100'
        ]);

        try {
            // Create unique filename
            $filename = Str::random(40) . '.mp3';

            // Create generation record
            $generation = Generation::create([
                'user_id' => auth()->id(),
                'title' => 'Test Generation #' . Str::random(8),
                'style' => $validated['soundfont'],
                'duration' => $validated['duration'],
                'happiness_level' => $validated['valence'] * 100,
                'energy_level' => $validated['arousal'] * 100,
                'status' => 'processing',
                'file_path' => $filename
            ]);

            // Create a dummy MP3 file (1 second of silence)
            $dummyMp3Content = base64_decode('SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4Ljc2LjEwMAAAAAAAAAAAAAAA//tQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAWGluZwAAAA8AAAACAAAFOwCenp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6enp6e//////////////////////////////////////////////////////////////////8AAAAATGF2YzU4LjEzAAAAAAAAAAAAAAAAJAX//////////////+IDkf////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8=');

            // Store the dummy file
            Storage::disk('public')->put('generations/' . $filename, $dummyMp3Content);

            // Update generation status
            $generation->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Music generated successfully',
                'generation' => $generation
            ]);

        } catch (\Exception $e) {
            if (isset($generation)) {
                $generation->update(['status' => 'failed']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate music: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Generation $generation)
    {
        if ($generation->user_id !== auth()->id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists('generations/' . $generation->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            'generations/' . $generation->file_path,
            $generation->title . '.mp3'
        );
    }

    public function play(Generation $generation)
    {
        if ($generation->user_id !== auth()->id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists('generations/' . $generation->file_path)) {
            abort(404);
        }

        return response()->file(
            Storage::disk('public')->path('generations/' . $generation->file_path),
            ['Content-Type' => 'audio/mpeg']
        );
    }
}
