@extends('layouts.student')

@section('content')
<link rel="stylesheet" href="{{ asset('css/finance-management.css') }}?v={{ filemtime(public_path('css/finance-management.css')) }}">
<div class="container finance-management" style="padding: 2rem 0;">
    <div style="margin-bottom: 2rem;">
        <h1 style="margin: 0 0 0.5rem 0;">Upload Payment Receipt</h1>
        <p style="margin: 0; color: #666;">Submit proof of payment for verification</p>
    </div>

    <!-- Payment Summary Cards -->
    <div class="finance-summary-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="border-left: 4px solid #3498db; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Total Tuition Fee</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #3498db; font-size: 2rem;">K{{ number_format($student->tuition_fee, 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #27ae60; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Amount Paid</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #27ae60; font-size: 2rem;">K{{ number_format($student->tuition_fee - $balance, 2) }}</h2>
        </div>
        <div class="card" style="border-left: 4px solid #e74c3c; padding: 1.25rem;">
            <p style="margin: 0; color: #666; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">Balance Due</p>
            <h2 style="margin: 0.5rem 0 0 0; color: #e74c3c; font-size: 2rem;">K{{ number_format($balance, 2) }}</h2>
        </div>
    </div>

    @if($errors->any())
        <div style="padding: 1rem; margin-bottom: 2rem; background: #f8d7da; border-left: 4px solid #e74c3c; border-radius: 0.25rem;">
            <strong style="color: #721c24;">Please fix the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 0; padding-left: 20px; color: #721c24;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="card">
        <h3 style="margin: 0 0 1.5rem 0; color: #2c3e50;">Upload Receipt</h3>
        
        <form action="{{ route('receipts.store', $student) }}" method="POST" enctype="multipart/form-data" id="receiptForm">
            @csrf

            <div class="finance-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label for="amount" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Amount Paid (K) <span style="color: #e74c3c;">*</span></label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" max="{{ $balance }}" required placeholder="0.00" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 1rem;" value="{{ old('amount') }}">
                    <small style="color: #666; font-size: 0.875rem;">Maximum: K{{ number_format($balance, 2) }}</small>
                </div>

                <div>
                    <label for="payment_date" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Payment Date</label>
                    <input type="date" id="payment_date" name="payment_date" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 1rem; background: white;" value="{{ old('payment_date', date('Y-m-d')) }}">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="payment_method" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Payment Method <span style="color: #e74c3c;">*</span></label>
                <select id="payment_method" name="payment_method" required style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.25rem; font-size: 1rem; background: white;">
                    <option value="">-- Select Payment Method --</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>📱 Mobile Money (MTN, Airtel, Zamtel)</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Cash (Paid at Office)</option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>💳 Card Payment</option>
                    <option value="zananco_billmaster" {{ old('payment_method') == 'zananco_billmaster' ? 'selected' : '' }}>🌐 Zananco Billmaster</option>
                </select>
                <small style="color: #666; font-size: 0.875rem;">Select how you made the payment</small>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="receipt_file" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50;">Receipt File <span style="color: #e74c3c;">*</span></label>
                <div class="finance-upload" style="border: 2px dashed #ddd; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f8f9fa;">
                    <input type="file" id="receipt_file" name="receipt_file" accept=".pdf,.jpg,.jpeg,.png" required style="display: none;" onchange="displayFileName(this)">
                    <label for="receipt_file" style="cursor: pointer; display: block;">
                        <div style="font-size: 3rem; color: #3498db; margin-bottom: 0.5rem;">📎</div>
                        <p style="margin: 0; color: #666; font-weight: 600;">Click to upload or drag and drop</p>
                        <p style="margin: 0.5rem 0 0 0; color: #999; font-size: 0.875rem;">PDF, JPG, PNG (Max 5MB)</p>
                        <p id="fileName" style="margin: 1rem 0 0 0; color: #27ae60; font-weight: 600;"></p>
                    </label>
                </div>
            </div>

            <div class="finance-actions" style="display: flex; gap: 1rem;">
                <button type="submit" style="flex: 1; padding: 1rem; background: #27ae60; color: white; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 1rem; font-weight: 600;">
                    ✓ Upload Receipt
                </button>
                <a href="{{ route('receipts.student-history') }}" style="flex: 1; padding: 1rem; background: #95a5a6; color: white; border: none; border-radius: 0.5rem; cursor: pointer; text-decoration: none; text-align: center; font-size: 1rem; font-weight: 600; display: block;">
                    ← Back to History
                </a>
            </div>
        </form>

    </div>

    <!-- Instructions Card -->
    <div class="card" style="background: #e3f2fd; border-left: 4px solid #1976d2;">
        <h3 style="margin: 0 0 1rem 0; color: #0d47a1;">📋 Upload Instructions</h3>
        <ol style="margin: 0; padding-left: 1.5rem; color: #0d47a1; line-height: 2;">
            <li>Take a <strong>clear photo or scan</strong> of your bank receipt or mobile money confirmation</li>
            <li>Ensure the <strong>amount, date, and transaction reference</strong> are clearly visible</li>
            <li>Supported formats: <strong>JPG, PNG, PDF</strong> (maximum 5MB)</li>
            <li>Enter the <strong>exact amount</strong> you paid</li>
            <li>Submit for accountant verification</li>
            <li>You'll receive notification once your receipt is <strong>approved or needs correction</strong></li>
        </ol>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileName = input.files[0]?.name;
    if (fileName) {
        document.getElementById('fileName').textContent = '✓ ' + fileName;
    }
}
</script>
@endsection
