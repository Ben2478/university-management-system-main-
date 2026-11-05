@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container mx-auto px-4 py-8 finance-management">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">New Payment</h1>
        <p class="text-gray-600 mt-2">for {{ $student->first_name }} {{ $student->last_name }}</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="finance-panel finance-form-panel bg-white rounded-lg shadow p-8 max-w-md">
        <form action="{{ route('payments.store', $student) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount (ZMW)</label>
                <input type="number"
                       id="amount"
                       name="amount"
                       step="0.01"
                       min="0.01"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="0.00"
                       required>
                @error('amount')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <input type="text"
                       id="description"
                       name="description"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Semester Tuition"
                       value="{{ old('description', 'Course Tuition Payment') }}">
                @error('description')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="finance-actions flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-md font-medium hover:bg-blue-700">
                    Proceed to Payment
                </button>
                <a href="{{ route('students.show', $student) }}" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-md font-medium hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 rounded text-sm text-blue-800">
            <p class="font-medium">Payment Gateway</p>
            <p class="mt-1">You will be redirected to Zananco Bank's BillMaster payment gateway to complete this transaction securely.</p>
        </div>
    </div>
</div>
@endsection
