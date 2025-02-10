{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="bg-gradient-to-b from-purple-900 via-purple-800 to-black min-h-screen">
        <div class="ml-64 p-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Profile Information
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Update your account's profile information and email address.
                                </p>
                            </header>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-4">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-white text-purple-600 hover:text-white border-2 border-purple-600 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-purple-600 focus:bg-purple-600 focus:text-white active:bg-purple-700 active:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-150">
                                        Save Changes
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                        <p class="text-sm text-gray-600">Saved.</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section class="space-y-6">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Delete Account
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Once your account is deleted, all of its resources and data will be permanently deleted.
                                </p>
                            </header>

                            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('delete')

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    @error('password', 'userDeletion')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition"
                                        onclick="return confirm('Are you sure you want to delete your account?')">
                                    Delete Account
                                </button>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
