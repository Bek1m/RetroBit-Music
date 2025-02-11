<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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
            'generation_length' => 'required|integer|min:100',
            'output_name' => 'required|string',
            'velocity_min' => 'required|integer|between:0,127',
            'velocity_max' => 'required|integer|between:0,127',
        ]);

        try {
            // Create generation record
            $generation = Generation::create([
                'user_id' => auth()->id(),
                'style' => $validated['soundfont'],
                'happiness_level' => $validated['valence'],
                'energy_level' => $validated['arousal'],
                'velocity_min' => $validated['velocity_min'],
                'velocity_max' => $validated['velocity_max'],
                'output_name' => $validated['output_name'] . '.mp3',
                'generation_length' => $validated['generation_length'],
                'tempo' => $validated['tempo']
            ]);

            $command = [
                'python3.10',
                '/Users/drinkurtishi/Desktop/AI-project/music_generator/scripts/labeled_trainer_generator_v3.py',
                'generate',
                '--output_name', $validated['output_name'],
                '--valence', $validated['valence'],
                '--arousal', $validated['arousal'],
                '--tempo', $validated['tempo'],
                '--velocity_min', $validated['velocity_min'], 
                '--velocity_max', $validated['velocity_max'], 
                '--generation_length', $validated['generation_length'],
                '--soundfont', $validated['soundfont']
            ];

            // Execute the Python command
            $process = new Process($command);
            $process->run();

            // Wait for the process to finish and get the output
            $process->wait();

                    // Capture the output
            $output = $process->getOutput();

            // Decode the JSON output
            $jsonOutput = json_decode($output, true);

            // Check if the JSON output is valid
            if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON output from Python script: ' . $output);
            }

            // Check if the process was successful
            if (!$process->isSuccessful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate music: '
                ], 500);
            }

            // Update generation status to completed
            $generation->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Music generated successfully',
                'generation' => $generation,
                'python_output' => $jsonOutput 
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
