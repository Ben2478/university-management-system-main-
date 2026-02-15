@extends(auth()->user()->role === 'super_admin' ? 'layouts.super-admin' : (auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.app'))

@section('content')
<div class="container" style="max-width: 800px; margin: 30px auto;">
    <h2>Review Receipt - {{ $receipt->student->user->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success" style="background: #27ae60; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Student</h4>
                <p style="font-size: 16px; font-weight: bold; color: #2c3e50;">{{ $receipt->student->user->name }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Amount</h4>
                <p style="font-size: 16px; font-weight: bold; color: #27ae60;">ZK {{ number_format($receipt->amount, 2) }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Upload Date</h4>
                <p style="font-size: 16px; color: #2c3e50;">{{ $receipt->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <h4 style="color: #34495e; margin-bottom: 10px;">Current Status</h4>
                <p style="font-size: 16px; font-weight: bold;">
                    @php
                        $statusColors = [
                            'pending' => '#f39c12',
                            'approved' => '#27ae60',
                            'rejected' => '#e74c3c'
                        ];
                    @endphp
                    <span style="padding: 5px 10px; background: {{ $statusColors[$receipt->status] ?? '#95a5a6' }}; color: white; border-radius: 4px; text-transform: capitalize;">{{ $receipt->status }}</span>
                </p>
            </div>
        </div>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

        <div style="margin-bottom: 20px;">
            <h4 style="color: #34495e; margin-bottom: 10px;">Receipt File</h4>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; text-align: center;">
                @if($receipt->file_path)
                    <a href="{{ asset('storage/' . $receipt->file_path) }}" target="_blank" class="btn-primary" style="padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">📥 Download Receipt</a>
                    <p style="margin-top: 10px; color: #7f8c8d; font-size: 14px;">{{ basename($receipt->file_path) }}</p>
                @else
                    <p style="color: #7f8c8d;">No file uploaded</p>
                @endif
            </div>
        </div>

        @if($receipt->description)
            <div style="margin-bottom: 20px;">
                <h4 style="color: #34495e; margin-bottom: 10px;">Description</h4>
                <p style="color: #555; line-height: 1.6;">{{ $receipt->description }}</p>
            </div>
        @endif
    </div>

    @if($receipt->status === 'pending')
        <div style="display: flex; gap: 10px;">
            <form action="{{ route('receipts.approve', $receipt) }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn-success" style="width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">✓ Approve Receipt</button>
            </form>
            <form action="{{ route('receipts.reject', $receipt) }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn-danger" style="width: 100%; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">✗ Reject Receipt</button>
            </form>
        </div>
    @else
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('receipts.index') }}" class="btn-secondary" style="flex: 1; padding: 12px; background: #95a5a6; color: white; text-decoration: none; border-radius: 4px; text-align: center; cursor: pointer; font-size: 16px;">Back to Receipts</a>
        </div>
    @endif
</div>
@endsection
