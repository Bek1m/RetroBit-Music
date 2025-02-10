<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Music Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
{{-- Sidebar --}}
<div class="fixed inset-y-0 left-0 w-64 bg-purple-900">
    <div class="flex items-center justify-center h-16 bg-purple-900">
        <span class="text-white text-xl font-bold">AI Music Studio</span>
    </div>
    <nav class="mt-8">
        <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-purple-800 {{ request()->routeIs('dashboard') ? 'bg-purple-800' : '' }}">
            <i class="fas fa-home mr-3"></i>
            Dashboard
        </a>
        <a href="{{ route('generations.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-purple-800 {{ request()->routeIs('generations.index') ? 'bg-purple-800' : '' }}">
            <i class="fas fa-music mr-3"></i>
            My Generations
        </a>
        <a href="{{ route('credits.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-purple-800 {{ request()->routeIs('credits.index') ? 'bg-purple-800' : '' }}">
            <i class="fas fa-coins mr-3"></i>
            Credits: {{ Auth::user()->creditBalance?->credits_balance ?? 0 }}
        </a>
    </nav>
</div>

{{-- Main Content --}}
<div class="ml-64 min-h-screen">
    {{-- Header --}}
    <div class="bg-white shadow">
        <div class="flex justify-between items-center px-8 py-4">
            <h1 class="text-2xl font-bold text-gray-800">Create New Music</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-gray-200 p-2 rounded-full hover:bg-gray-300 focus:outline-none">
                        <i class="fas fa-user text-gray-600"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-user mr-2"></i> My Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="p-8">
        <div class="flex gap-8">
            {{-- Left Side - Music Parameters --}}
            <div class="w-2/3">
                <form id="generationForm">
                    @csrf
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-6">Music Parameters</h2>
                        <div class="space-y-6">
                            {{-- Happiness Level Slider --}}
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <label class="text-sm font-medium text-gray-700">Happiness Level (Valence)</label>
                                    <span class="text-sm text-gray-500" id="valenceValue">0.0</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-frown text-gray-400"></i>
                                    <input type="range" name="valence"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                           min="-100" max="100" value="0"
                                           oninput="updateSliderValue('valenceValue', this.value, true)">
                                    <i class="fas fa-smile text-gray-400"></i>
                                </div>
                            </div>

                            {{-- Energy Level Slider --}}
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <label class="text-sm font-medium text-gray-700">Energy Level (Arousal)</label>
                                    <span class="text-sm text-gray-500" id="arousalValue">0.0</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <i class="fas fa-moon text-gray-400"></i>
                                    <input type="range" name="arousal"
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                           min="-100" max="100" value="0"
                                           oninput="updateSliderValue('arousalValue', this.value, true)">
                                    <i class="fas fa-sun text-gray-400"></i>
                                </div>
                            </div>

                            {{-- Tempo Slider --}}
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <label class="text-sm font-medium text-gray-700">Tempo</label>
                                    <span class="text-sm text-gray-500" id="tempoValue">120 BPM</span>
                                </div>
                                <input type="range" name="tempo"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                       min="60" max="180" value="120"
                                       oninput="updateSliderValue('tempoValue', this.value, false)">
                            </div>

                            {{-- Sound Style --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Sound Style</label>
                                <div class="grid grid-cols-3 gap-4">
                                    @foreach(['contra' => 'Contra', 'nintendo' => 'Nintendo', 'violin' => 'Violin'] as $value => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="soundfont" value="{{ $value }}"
                                                   class="peer hidden" {{ $value === 'contra' ? 'checked' : '' }}>
                                            <div class="p-4 border border-gray-200 rounded-lg text-center
                                                            peer-checked:border-purple-500 peer-checked:bg-purple-50 hover:bg-gray-50">
                                                <i class="fas {{ $value === 'violin' ? 'fa-music' : 'fa-gamepad' }}
                                                          text-xl {{ $value === 'contra' ? 'text-purple-500' : 'text-gray-400' }}"></i>
                                                <p class="mt-1 text-sm font-medium text-gray-700">{{ $label }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Duration --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Duration</label>
                                <select name="duration" class="w-full border-gray-300 rounded-md shadow-sm"
                                        onchange="updateGenerationLength(this.value)">
                                    <option value="30">30 seconds (1 credit)</option>
                                    <option value="60">60 seconds (2 credits)</option>
                                    <option value="120">120 seconds (4 credits)</option>
                                </select>
                                <input type="hidden" name="generation_length" value="240">
                            </div>

                            <button type="submit"
                                    class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700
                                               transition duration-200 flex items-center justify-center gap-2">
                                <i class="fas fa-magic"></i>
                                <span>Generate Music</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right Side - Recent Generations --}}
            <div class="w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-6">Recent Generations</h2>
                    <div class="space-y-4">
                        @forelse($recentGenerations ?? [] as $generation)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-gray-700">{{ $generation->title }}</span>
                                    <span class="text-xs {{ $generation->status === 'completed' ? 'text-green-600 bg-green-100' : 'text-yellow-600 bg-yellow-100' }} px-2 py-1 rounded-full">
                                            {{ ucfirst($generation->status) }}
                                        </span>
                                </div>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>{{ $generation->duration }}s • {{ ucfirst($generation->style) }}</span>
                                    <div class="flex space-x-2">
                                        @if($generation->status === 'completed')
                                            <button onclick="playGeneration('{{ $generation->id }}')" class="hover:text-purple-600">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <a href="{{ route('generations.download', $generation) }}" class="hover:text-purple-600">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <div class="animate-spin text-purple-600">
                                                <i class="fas fa-spinner"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 text-center py-4">No generations yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Audio Player --}}
    <div class="fixed bottom-0 inset-x-0 bg-white border-t shadow-lg p-4 ml-64">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-purple-600">
                    <i class="fas fa-step-backward"></i>
                </button>
                <button class="w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center hover:bg-purple-700">
                    <i class="fas fa-play"></i>
                </button>
                <button class="text-gray-600 hover:text-purple-600">
                    <i class="fas fa-step-forward"></i>
                </button>
            </div>
            <div class="flex-grow mx-8">
                <div class="text-sm text-gray-600 mb-1">Happy Tune #1</div>
                <div class="h-1 bg-gray-200 rounded">
                    <div class="w-1/3 h-full bg-purple-600 rounded"></div>
                </div>
            </div>
            <div class="flex items-center space-x-4 text-gray-600">
                <span class="text-sm">1:23 / 3:45</span>
                <button class="hover:text-purple-600">
                    <i class="fas fa-volume-up"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateSliderValue(elementId, value, normalize = false) {
        const displayValue = normalize ?
            (parseFloat(value) / 100).toFixed(1) :
            parseInt(value);

        const suffix = elementId === 'tempoValue' ? ' BPM' : '';
        document.getElementById(elementId).textContent = displayValue + suffix;
    }

    function updateGenerationLength(duration) {
        const generationLength = duration * 8;
        document.querySelector('input[name="generation_length"]').value = generationLength;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSliderValue('valenceValue', document.querySelector('input[name="valence"]').value, true);
        updateSliderValue('arousalValue', document.querySelector('input[name="arousal"]').value, true);
        updateSliderValue('tempoValue', document.querySelector('input[name="tempo"]').value, false);

        const defaultDuration = document.querySelector('select[name="duration"]').value;
        updateGenerationLength(defaultDuration);
    });

    document.getElementById('generationForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

        try {
            const formData = new FormData(form);
            formData.set('valence', parseFloat(formData.get('valence')) / 100);
            formData.set('arousal', parseFloat(formData.get('arousal')) / 100);

            const response = await fetch('{{ route('music.generate') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Your music is being generated.',
                    icon: 'success',
                    confirmButtonColor: '#9333ea'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to generate music: ' + error.message,
                icon: 'error',
                confirmButtonColor: '#9333ea'
            });
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-magic"></i> Generate Music';
        }
    });

    function playGeneration(id) {
        const audioPlayer = document.getElementById('audioPlayer');
        if (audioPlayer) {
            const playUrl = `/generations/${id}/play`;
            audioPlayer.src = playUrl;
            audioPlayer.play();
        }
    }

    if (!document.getElementById('audioPlayer')) {
        const audioPlayer = document.createElement('audio');
        audioPlayer.id = 'audioPlayer';
        audioPlayer.controls = true;
        audioPlayer.classList.add('hidden');
        document.body.appendChild(audioPlayer);
    }
</script>
</body>
</html>
