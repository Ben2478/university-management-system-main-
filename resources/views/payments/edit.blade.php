@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container finance-management" style="max-width: 600px; margin: 30px auto;">
    <h2>Edit Payment</h2>

    @if($errors->any())
        <div class="alert alert-error" style="background: #e74c3c; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="finance-panel" action="{{ route('payments.update', $payment) }}" method="POST" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label for="student_id" style="display: block; margin-bottom: 8px; font-weight: bold;">Student:</label>
            <select id="student_id" name="student_id" required disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f5f5f5;">
                <option value="{{ $payment->student_id }}">{{ $payment->student->user->name }}</option>
            </select>
            <input type="hidden" name="student_id" value="{{ $payment->student_id }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="amount" style="display: block; margin-bottom: 8px; font-weight: bold;">Amount (ZK):</label>
            <input type="number" id="amount" name="amount" min="0" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $payment->amount }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="payment_date" style="display: block; margin-bottom: 8px; font-weight: bold;">Payment Date:</label>
            <input type="date" id="payment_date" name="payment_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" value="{{ $payment->payment_date->format('Y-m-d') }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="payment_status" style="display: block; margin-bottom: 8px; font-weight: bold;">Status:</label>
            <select id="payment_status" name="payment_status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="pending" {{ $payment->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $payment->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $payment->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="cancelled" {{ $payment->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="payment_method" style="display: block; margin-bottom: 8px; font-weight: bold;">Payment Method:</label>
            <select id="payment_method" name="payment_method" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="zananco" {{ $payment->payment_method == 'zananco' ? 'selected' : '' }}>Zananco Bank</option>
                <option value="direct_deposit" {{ $payment->payment_method == 'direct_deposit' ? 'selected' : '' }}>Direct Deposit</option>
                <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="cheque" {{ $payment->payment_method == 'cheque' ? 'selected' : '' }}>Cheque</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold;">Description:</label>
            <textarea id="description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $payment->description }}</textarea>
        </div>

        <div class="finance-actions" style="display: flex; gap: 10px;">
            <button type="submit" class="btn-success" style="flex: 1; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Update Payment</button>
            <a href="{{ route('payments.index') }}" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; text-align: center; font-size: 16px;">Cancel</a>
        </div>
    </form>
</div>
@endsection
