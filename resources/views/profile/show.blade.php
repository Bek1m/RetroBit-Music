{{-- resources/views/profile/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="ml-64 p-8">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-white">My Profile</h1>
        <a href="{{ route('profile.edit') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-edit mr-2"></i>Edit Profile
        </a>
    </div>

    {{-- Profile Content --}}
    <div class="bg-white rounded-lg shadow-md">
        {{-- Profile Header --}}
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-user text-4xl text-purple-600"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Account Details --}}
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Account Details</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Member Since</p>
                    <p class="font-medium">{{ Auth::user()->created_at->format('F j, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Account Status</p>
                    <p class="font-medium">
                        <span class="text-green-600 bg-green-100 px-2 py-1 rounded-full text-sm">
                            Active
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Usage Statistics --}}
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-600 mb-1">{{ $totalGenerations }}</div>
                <p class="text-sm text-gray-600">Total Generations</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-600 mb-1">{{ $creditsRemaining }}</div>
                <p class="text-sm text-gray-600">Credits Remaining</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-600 mb-1">{{ $downloadsCount }}</div>
                <p class="text-sm text-gray-600">Total Downloads</p>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @forelse($recentActivity ?? [] as $activity)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-music text-purple-600"></i>
                            <div>
                                <p class="font-medium text-gray-800">{{ $activity->title }}</p>
                                <p class="text-sm text-gray-600">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span class="text-sm text-gray-600">{{ $activity->type }}</span>
                    </div>
                @empty
                    <p class="text-gray-600 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
