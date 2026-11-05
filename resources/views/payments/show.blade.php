@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container mx-auto px-4 py-8 finance-management">
    <div class="mb-8">
        <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-900">&larr; Back to Payments</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">Payment Details</h1>
    </div>

    <div class="finance-detail-grid grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="finance-panel md:col-span-2 bg-white rounded-lg shadow p-8">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                <div class="finance-grid grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Student</p>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $payment->student->first_name }} {{ $payment->student->last_name }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $payment->student->student_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Amount</p>
                        <p class="text-lg font-bold text-gray-900">K{{ number_format($payment->amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Reference Number:</span>
                        <span class="text-sm font-mono text-gray-900">{{ $payment->reference_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Transaction ID:</span>
                        <span class="text-sm font-mono text-gray-900">{{ $payment->transaction_id ?? 'Pending' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Method:</span>
                        <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($payment->status === 'completed') bg-green-100 text-green-800
                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'processing') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                <p class="text-gray-700">{{ $payment->description }}</p>
            </div>

            @if($payment->notes)
                <hr class="my-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                    <p class="text-gray-700">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Timeline -->
        <div class="finance-panel bg-white rounded-lg shadow p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Created</p>
                    <p class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Updated</p>
                    <p class="text-sm font-medium text-gray-900">{{ $payment->updated_at->format('M d, Y H:i') }}</p>
                </div>

                @if($payment->paid_at)
                    <div>
                        <p class="text-sm text-gray-600">Paid</p>
                        <p class="text-sm font-medium text-gray-900">{{ $payment->paid_at->format('M d, Y H:i') }}</p>
                    </div>
                @endif
            </div>

            @if($payment->status !== 'completed')
                <form action="{{ route('payments.verify', $payment) }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md font-medium hover:bg-green-700">
                        Verify Payment
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($payment->gateway_response)
        <div class="finance-panel mt-6 bg-white rounded-lg shadow p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Gateway Response</h2>
            <pre class="bg-gray-100 p-4 rounded text-xs overflow-auto">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
</div>
@endsection
