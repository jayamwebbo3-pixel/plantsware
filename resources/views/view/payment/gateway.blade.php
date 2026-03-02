@include('view.layout.header')

<div class="container py-5 text-center mt-5 mb-5">
    <h2>Secure Payment Gateway (Simulation)</h2>
    <p>Transaction ID: {{ $transaction->transaction_ref }}</p>
    <p>Amount to Pay: <strong>₹{{ number_format($transaction->amount, 2) }}</strong></p>
    
    <div class="mt-4">
        <!-- Simulate Success -->
        <form action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="SUCCESS">
            <button type="submit" class="btn btn-success btn-lg">Simulate Payment Success</button>
        </form>

        <!-- Simulate Failure -->
        <form action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="FAILED">
            <button type="submit" class="btn btn-danger btn-lg">Simulate Payment Failure</button>
        </form>
    </div>
    
    <p class="mt-3 text-muted"><small>Note: This transaction will automatically expire in 10 minutes from creation if pending.</small></p>
</div>

@include('view.layout.footer')
