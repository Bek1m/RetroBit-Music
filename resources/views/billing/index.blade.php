{{-- resources/views/billing/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="ml-64 p-8">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-white">Billing & Subscription</h1>
        <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
            <i class="fas fa-plus mr-2"></i>Buy Credits
        </button>
    </div>

    {{-- Current Plan --}}
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Current Plan</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-crown text-xl text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $subscription->name ?? 'Free Plan' }}</h3>
                            <p class="text-sm text-gray-600">{{ $subscription->description ?? 'Basic features included' }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $subscription->generations_per_month ?? '10' }} generations per month</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $subscription->max_duration ?? '60' }} seconds per generation</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 mb-1">Next billing date</p>
                    <p class="font-medium text-gray-800">{{ $subscription->next_billing_date ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Credit Packages --}}
    <div class="grid grid-cols-3 gap-6">
        @foreach($creditPackages as $package)
            <div class="bg-white rounded-lg shadow-md p-6 border-2 border-transparent hover:border-purple-500 transition">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $package->name }}</h3>
                    <p class="text-3xl font-bold text-purple-600 my-2">${{ number_format($package->price, 2) }}</p>
                    <p class="text-sm text-gray-600">{{ $package->credits }} credits</p>
                </div>
                <ul class="space-y-2 mb-6">
                    @foreach($package->features as $feature)
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
                <button class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
                    Purchase
                </button>
            </div>
        @endforeach
    </div>

    {{-- Billing History --}}
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Billing History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($billingHistory ?? [] as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $transaction->date }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $transaction->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            ${{ $transaction->amount }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $transaction->status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $transaction->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-purple-600">
                            <a href="{{ $transaction->invoice_url }}" class="hover:text-purple-700">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-600">
                            No billing history available
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
