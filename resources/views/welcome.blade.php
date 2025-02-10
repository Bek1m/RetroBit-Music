<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RetroBit - AI Music Generation</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-b from-purple-900 via-purple-800 to-black text-white antialiased">
    <!-- Navigation -->
    <nav class="flex items-center justify-between p-6">
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18V5l12-2v13"></path>
                <circle cx="6" cy="18" r="3"></circle>
                <circle cx="18" cy="16" r="3"></circle>
            </svg>
            <span class="text-2xl font-bold">RetroBit</span>
        </div>
        <div class="flex space-x-6">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="hover:text-purple-300 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-purple-300 transition">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-purple-500 px-4 py-2 rounded-full hover:bg-purple-600 transition">Get Started</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="max-w-6xl mx-auto px-6 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-bold mb-6">Create Music with AI</h1>
            <p class="text-xl text-purple-200 mb-12 max-w-2xl mx-auto">
                Transform your ideas into unique tracks using cutting-edge AI technology.
                Generate melodies, harmonies, and complete songs in seconds.
            </p>
            <div class="flex justify-center gap-6">
                <a href="{{ route('register') }}" class="bg-purple-500 px-8 py-3 rounded-full text-lg font-semibold hover:bg-purple-600 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                    Start Creating
                </a>
                <button class="bg-purple-900 px-8 py-3 rounded-full text-lg font-semibold hover:bg-purple-800 transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polygon points="10 8 16 12 10 16 10 8"></polygon>
                    </svg>
                    Try Demo
                </button>
            </div>
        </div>

        <!-- Features -->
        <div class="grid md:grid-cols-3 gap-8 mt-24">
            <div class="bg-purple-900/50 p-6 rounded-xl">
                <div class="bg-purple-500 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="23"></line>
                        <line x1="8" y1="23" x2="16" y2="23"></line>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Voice to Music</h3>
                <p class="text-purple-200">
                    Hum or sing your melody and watch as AI transforms it into a full composition
                </p>
            </div>

            <div class="bg-purple-900/50 p-6 rounded-xl">
                <div class="bg-purple-500 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Smart Generation</h3>
                <p class="text-purple-200">
                    Advanced AI models understand music theory to create harmonious and original tracks
                </p>
            </div>

            <div class="bg-purple-900/50 p-6 rounded-xl">
                <div class="bg-purple-500 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Easy Sharing</h3>
                <p class="text-purple-200">
                    Share your creations instantly or download high-quality audio files
                </p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-24">
            <h2 class="text-3xl font-bold mb-6">Ready to Make Music?</h2>
            <p class="text-purple-200 mb-8">
                Join thousands of creators using AI to enhance their music production
            </p>
            <a href="{{ route('register') }}" class="bg-purple-500 px-8 py-3 rounded-full text-lg font-semibold hover:bg-purple-600 transition inline-block">
                Sign Up Now
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-purple-800 mt-24 py-8 text-center text-purple-300">
        <p>© {{ date('Y') }} RetroBit. All rights reserved.</p>
    </footer>

</body>
</html>
