@include('view.layout.header')

<div class="container py-5 text-center mt-5 mb-5">
    <div class="mb-3">
        <!-- <i class="fas fa-wallet fa-4x text-primary mb-3"></i> -->
        <h5 class="text-warning mb-3"><i class="fas fa-spinner fa-spin me-2"></i> Payment Pending...</h5>
    </div>
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
        <form id="simulate-fail-form" action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="FAILED">
            <button type="submit" class="btn btn-danger btn-lg">Simulate Payment Failure</button>
        </form>
        <!-- Simulate 10 Mins Expire & Cron -->
        <!-- <form action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="EXPIRE_DEMO">
            <button type="submit" class="btn btn-warning btn-lg">Simulate 10 Mins Timeout (Cron Demo)</button>
        </form> -->
    </div>

    <p class="mt-4 text-muted border p-3 rounded bg-light mx-auto" style="max-width: 400px;">
        <i class="fas fa-clock fs-4 mb-2 text-warning"></i><br>
        <strong>Time Remaining to Pay:</strong><br>
        <span id="countdown-timer" class="fs-2 font-monospace text-dark fw-bold"></span>
    </p>
    
    <p class="mt-3 text-muted"><small>Note: This transaction automatically expires exactly 10 minutes from creation.</small></p>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Retrieve PHP variables
        const createdAt = new Date("{{ $transaction->created_at->toISOString() }}").getTime();
        const tenMinutesInMillis = 10 * 60 * 1000;
        const expiryTime = createdAt + tenMinutesInMillis;

        function updateTimer() {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance <= 0) {
                // Timer expired
                document.getElementById('countdown-timer').innerHTML = "00:00 - EXPIRED";
                document.getElementById('countdown-timer').classList.replace("text-dark", "text-danger");
                
                // Disable success button
                const successBtn = document.querySelector('button.btn-success');
                if(successBtn) successBtn.disabled = true;

                // Auto-trigger failure if still on page
                const failForm = document.getElementById('simulate-fail-form');
                if(failForm) {
                    setTimeout(() => failForm.submit(), 1500); // submit failure after 1.5 seconds
                }
                return;
            }

            // Calculate minutes and seconds
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Format numbers to always be 2 digits
            const formattedMin = minutes < 10 ? "0" + minutes : minutes;
            const formattedSec = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById('countdown-timer').innerHTML = formattedMin + ":" + formattedSec;

            // Update again in 1 second
            setTimeout(updateTimer, 1000);
        }

        // Start countdown
        updateTimer();
    });
</script>

@include('view.layout.footer')
