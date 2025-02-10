{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Music Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
{{-- Sidebar --}}
<div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-purple-900 via-purple-800 to-black">
    <div class="flex items-center justify-center h-16 bg-purple-900">
        <span class="text-white text-xl font-bold">AI Music Studio</span>
    </div>
    <nav class="mt-8">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="flex items-bottom px-6 py-3 text-white hover:bg-purple-700 {{ request()->routeIs('dashboard') ? 'bg-purple-700' : '' }}">
            <i class="fas fa-home mr-3"></i>
            Dashboard
        </a>

        <!-- My Generations Link -->
        <a href="{{ route('generations.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-purple-700 {{ request()->routeIs('generations.index') ? 'bg-purple-700' : '' }}">
            <i class="fas fa-music mr-3"></i>
            My Generations
        </a>

        <!-- Credits Link -->
        <a href="{{ route('credits.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-purple-700 {{ request()->routeIs('credits.index') ? 'bg-purple-700' : '' }}">
            <i class="fas fa-coins mr-3"></i>
            Credits: {{ Auth::user()->creditBalance?->credits_balance ?? 0 }}
        </a>
    </nav>
</div>


{{-- Main Content --}}
    <div class="ml-64 p-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create New Music</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Welcome, {{ Auth::user()->name }}</span>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-gray-200 p-2 rounded-full hover:bg-gray-300 focus:outline-none">
                        <i class="fas fa-user text-gray-600"></i>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-show="open"
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-user-edit mr-2"></i> Edit Profile
                        </a>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-id-card mr-2"></i> My Profile
                        </a>
                        <a href="{{ route('billing.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                            <i class="fas fa-credit-card mr-2"></i> Billing
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Generation Card --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Left Column: Controls --}}
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Music Parameters</h2>

                    {{-- Emotion Sliders --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Happiness Level
                        </label>
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-frown text-gray-400"></i>
                            <input type="range" class="flex-grow h-2 bg-gray-200 rounded-lg appearance-none"
                                   min="0" max="100" value="50">
                            <i class="fas fa-smile text-gray-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Energy Level
                        </label>
                        <div class="flex items-center space-x-4">
                            <i class="fas fa-moon text-gray-400"></i>
                            <input type="range" class="flex-grow h-2 bg-gray-200 rounded-lg appearance-none"
                                   min="0" max="100" value="50">
                            <i class="fas fa-sun text-gray-400"></i>
                        </div>
                    </div>

                    {{-- Style Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Music Style</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="p-4 border-2 border-purple-500 rounded-lg text-center hover:bg-purple-50">
                                <i class="fas fa-gamepad text-2xl text-purple-500 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">8-bit Style</p>
                            </button>
                            <button class="p-4 border-2 border-gray-200 rounded-lg text-center hover:bg-gray-50">
                                <i class="fas fa-tv-retro text-2xl text-gray-400 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Nintendo Style</p>
                            </button>
                        </div>
                    </div>

                    {{-- Duration Selection --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                        <select class="w-full border-gray-300 rounded-md shadow-sm">
                            <option>30 seconds (1 credit)</option>
                            <option>60 seconds (2 credits)</option>
                            <option>120 seconds (4 credits)</option>
                        </select>
                    </div>

                    <button class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700
                                 transition duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-magic"></i>
                        <span>Generate Music</span>
                    </button>
                </div>

                {{-- Right Column: Recent Generations --}}
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Recent Generations</h2>
                    <div class="space-y-4">
                        {{-- Generation Item --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">Happy Tune #1</span>
                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                    Complete
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>30 seconds • 8-bit</span>
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

                        {{-- Generation Item (Processing) --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-700">Energetic Beat #2</span>
                                <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">
                                    Processing
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>60 seconds • Nintendo</span>
                                <div class="animate-pulse">
                                    <i class="fas fa-spinner fa-spin text-purple-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="block text-center text-purple-600 hover:text-purple-700 mt-4">
                        View All Generations →
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Audio Player (Fixed at bottom) --}}
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
</body>
</html>
