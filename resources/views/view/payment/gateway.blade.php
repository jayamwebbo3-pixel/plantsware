@include('view.layout.header')

<div class="container py-5 text-center mt-5 mb-5">
    <!-- Central Professional Loader (Hidden by Default) -->
    <div id="main-payment-loader" class="mb-5 d-none flex-column align-items-center justify-content-center" data-aos="fade-down" data-aos-duration="800">
        <div class="modern-loader mb-4">
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-circle"></div>
            <div class="loader-shadow"></div>
            <div class="loader-shadow"></div>
            <div class="loader-shadow"></div>
        </div>
        <h4 class="text-success fw-bold mb-2" style="letter-spacing: 1px;">Processing Payment</h4>
        <p class="text-muted small mb-0">Please wait while we secure your transaction...</p>
    </div>

    <style>
        /* Modern Professional Loader */
        .modern-loader {
            width: 120px;
            height: 60px;
            position: relative;
            z-index: 1;
            margin: 0 auto;
        }
        .loader-circle {
            width: 20px;
            height: 20px;
            position: absolute;
            border-radius: 50%;
            background-color: var(--primary-color, #1b8744);
            left: 10px;
            transform-origin: 50%;
            animation: loader-circle .5s alternate infinite ease;
        }
        .loader-circle:nth-child(2) {
            left: 50px;
            animation-delay: .2s;
        }
        .loader-circle:nth-child(3) {
            left: 90px;
            right: auto;
            animation-delay: .3s;
        }
        .loader-shadow {
            width: 20px;
            height: 4px;
            border-radius: 50%;
            background-color: rgba(0,0,0,0.1);
            position: absolute;
            top: 40px;
            transform-origin: 50%;
            z-index: -1;
            left: 10px;
            filter: blur(1px);
            animation: loader-shadow .5s alternate infinite ease;
        }
        .loader-shadow:nth-child(5) {
            left: 50px;
            animation-delay: .2s;
        }
        .loader-shadow:nth-child(6) {
            left: 90px;
            right: auto;
            animation-delay: .3s;
        }
        @keyframes loader-circle {
            0% {
                top: 30px;
                height: 5px;
                border-radius: 50px 50px 25px 25px;
                transform: scaleX(1.7);
            }
            40% {
                height: 20px;
                border-radius: 50%;
                transform: scaleX(1);
            }
            100% {
                top: 0%;
            }
        }
        @keyframes loader-shadow {
            0% {
                transform: scaleX(1.5);
            }
            40% {
                transform: scaleX(1);
                opacity: .7;
            }
            100% {
                transform: scaleX(.2);
                opacity: .4;
            }
        }
    </style>
    <h2>Secure Payment Gateway (Simulation)</h2>
    <p>Transaction ID: {{ $transaction->transaction_ref }}</p>
    <p>Amount to Pay: <strong>₹{{ number_format($transaction->amount, 2) }}</strong></p>
    
    <div class="mt-4">
        <!-- Simulate Success -->
        <form action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2" onsubmit="return processPayment(event, this, 'success')">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="SUCCESS">
            <button type="submit" class="btn btn-success btn-lg px-4 position-relative" id="btn-success">
                <span class="btn-text">Simulate Payment Success</span>
                <div class="spinner-border spinner-border-sm text-white position-absolute top-50 start-50 translate-middle d-none" id="spinner-success" role="status"></div>
            </button>
        </form>
        <!-- Simulate Failure -->
        <form id="simulate-fail-form" action="{{ route('payment.callback') }}" method="POST" class="d-inline-block m-2" onsubmit="return processPayment(event, this, 'failure')">
            @csrf
            <input type="hidden" name="transaction_ref" value="{{ $transaction->transaction_ref }}">
            <input type="hidden" name="status" value="FAILED">
            <button type="submit" class="btn btn-danger btn-lg px-4 position-relative" id="btn-failure">
                <span class="btn-text">Simulate Payment Failure</span>
                <div class="spinner-border spinner-border-sm text-white position-absolute top-50 start-50 translate-middle d-none" id="spinner-failure" role="status"></div>
            </button>
        </form>
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

    // Handle Payment Simulation with Timeout
    function processPayment(event, form, type) {
        event.preventDefault(); // Stop immediate submission

        // Get elements
        const btnSuccess = document.getElementById('btn-success');
        const btnFailure = document.getElementById('btn-failure');
        const activeBtn = type === 'success' ? btnSuccess : btnFailure;
        const activeSpinner = document.getElementById('spinner-' + type);
        const activeText = activeBtn.querySelector('.btn-text');
        const mainLoader = document.getElementById('main-payment-loader');

        // Disable all buttons
        btnSuccess.disabled = true;
        btnFailure.disabled = true;

        if (type === 'success') {
            // Show the professional central loader
            mainLoader.classList.remove('d-none');
            mainLoader.classList.add('d-flex');
            
            // Optionally fade out the buttons and gateway text for a cleaner look
            document.querySelector('h2').style.opacity = '0.3';
            document.querySelector('.mt-4').style.opacity = '0.3';
        } else {
            // Standard small spinner for failure
            activeBtn.classList.add('opacity-75');
            activeText.classList.add('invisible'); // hide text but keep button width
            activeSpinner.classList.remove('d-none');
        }

        // Wait 3 seconds to simulate processing, then submit the form
        setTimeout(() => {
            form.submit();
        }, 3000);
        
        return false;
    }
</script>

@include('view.layout.footer')
