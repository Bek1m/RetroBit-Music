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
                                <i class="fas fa-music text-purple-600"></i>
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $generation->title }}</h3>
                                    <p class="text-sm text-gray-600">{{ $generation->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">{{ $generation->duration }}s • {{ $generation->style }}</span>
                                <div class="flex space-x-2">
                                    <button class="hover:text-purple-600">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button class="hover:text-purple-600">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="hover:text-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600">
                        No generations yet. Start creating music!
                    </div>
                @endforelse

                <div class="px-6 py-4">
                    {{ $generations->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
