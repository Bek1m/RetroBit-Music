@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-purple-900 via-purple-800 to-black min-h-screen">
        <div class="ml-64 p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-white">My Generations</h1>
            </div>

            <div class="bg-white rounded-lg shadow">
                @forelse($generations as $generation)
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas 
                                    @if($generation->style === 'contra')
                                        fa-gamepad
                                    @elseif($generation->style === 'nintendo')
                                        fa-ghost
                                    @elseif($generation->style === 'violin')
                                        fa-music
                                    @elseif($generation->style === 'piano')
                                        fa-piano
                                    @else
                                        fa-music
                                    @endif
                                    text-purple-600"></i>
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $generation->output_name }}</h3>
                                    <p class="text-sm text-gray-600">
                                        Created {{ $generation->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">
                                    {{ $generation->generation_length }} notes • 
                                    {{ ucfirst($generation->style) }} • 
                                    {{ $generation->tempo }} BPM
                                </span>
                                <div class="flex space-x-2">
                                    <button onclick="playGeneration('{{ $generation->id }}')" 
                                            class="hover:text-purple-600 transition-colors">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <a href="{{ route('generations.download', $generation) }}" 
                                       class="hover:text-purple-600 transition-colors">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('generations.destroy', $generation) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this generation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hover:text-red-600 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500">
                            <span class="mr-4">Happiness: {{ number_format($generation->happiness_level, 2) }}</span>
                            <span class="mr-4">Energy: {{ number_format($generation->energy_level, 2) }}</span>
                            <span>Velocity: {{ $generation->velocity_min }}-{{ $generation->velocity_max }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600">
                        <i class="fas fa-music text-gray-400 text-4xl mb-4"></i>
                        <p>No generations yet. Start creating music!</p>
                    </div>
                @endforelse

                <div class="px-6 py-4">
                    {{ $generations->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection