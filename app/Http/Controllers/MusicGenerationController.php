<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MusicGenerationController extends Controller
{
    
    public function generate(Request $request)
    {
        set_time_limit(3600);
        $validated = $request->validate([
            'valence' => 'required|numeric|between:-1,1',
            'arousal' => 'required|numeric|between:-1,1',
            'tempo' => 'required|integer|between:0,200',
            'soundfont' => 'required|in:contra,nintendo,violin,piano',
            'generation_length' => 'required|integer|min:50',
            'output_name' => 'required|string',
            'velocity_min' => 'required|integer|between:0,127',
            'velocity_max' => 'required|integer|between:0,127',
        ]);

        try {
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

            $process = new Process($command);
            $process->setTimeout(3600);
            $process->run();
             
            $process->wait();
            $output = $process->getOutput();

            $jsonOutput = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON output from Python script: ' . $output);
            }
            if (!$process->isSuccessful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate music: '
                ], 500);
            }
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
            abort(403, 'Unauthorized action.');
        }
        $filePath = '/Users/drinkurtishi/Desktop/AI-project/music_generator/data/generated_music_mp3/' . $generation->output_name;
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        return response()->download($filePath, $generation->output_name);
    }

    public function play(Generation $generation)
    {
        if ($generation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $filePath = '/Users/drinkurtishi/Desktop/AI-project/music_generator/data/generated_music_mp3/'.$generation->output_name;
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        return response()->file($filePath, ['Content-Type' => 'audio/mpeg']);
    }

    public function destroy(Generation $generation)
    {
        if ($generation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $filePath = '/Users/drinkurtishi/Desktop/AI-project/music_generator/data/generated_music_mp3/' . $generation->output_name;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $generation->delete();
        return response()->json(['message' => 'Generation deleted successfully.'], 200);
    }
}
