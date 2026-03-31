@include('view.layout.header')

<style>
    .order-confirmation-container {
        background-color: #f8fbf5;
        padding: 60px 0;
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .confirmation-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(118, 167, 19, 0.1);
        padding: 50px 40px;
        max-width: 650px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    /* Subtle plant leaf background accent */
    .confirmation-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background-image: radial-gradient(circle, rgba(118,167,19,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }

    /* Checkmark Animation */
    .success-checkmark {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px auto;
        position: relative;
        z-index: 1;
    }
    .success-checkmark .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid var(--primary-color, #76a713);
        animation: scaleAnimation 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }
    .success-checkmark .check-icon::before {
        top: 3px;
        left: -2px;
        width: 30px;
        transform-origin: 100% 50%;
        border-radius: 100px 0 0 100px;
    }
    .success-checkmark .check-icon::after {
        top: 0;
        left: 30px;
        width: 60px;
        transform-origin: 0 50%;
        border-radius: 0 100px 100px 0;
        animation: iconCheck 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    .success-checkmark .check-icon::before, .success-checkmark .check-icon::after {
        content: '';
        height: 100px;
        position: absolute;
        background: #fff;
        transform: rotate(-45deg);
    }
    .success-checkmark .check-line {
        height: 5px;
        background-color: var(--primary-color, #76a713);
        display: block;
        border-radius: 2px;
        position: absolute;
        z-index: 10;
    }
    .success-checkmark .check-line.line-tip {
        top: 46px;
        left: 14px;
        width: 25px;
        transform: rotate(45deg);
        animation: iconLineTip 0.4s ease forwards;
    }
    .success-checkmark .check-line.line-long {
        top: 38px;
        right: 8px;
        width: 47px;
        transform: rotate(-45deg);
        animation: iconLineLong 1s forwards;
    }
    .success-checkmark .check-circle {
        top: -4px;
        left: -4px;
        z-index: 10;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        position: absolute;
        box-sizing: content-box;
        border: 4px solid rgba(118,167,19,0.2);
    }
    .success-checkmark .check-fix {
        top: 8px;
        width: 5px;
        left: 26px;
        z-index: 1;
        height: 85px;
        position: absolute;
        transform: rotate(-45deg);
        background-color: #fff;
    }

    @keyframes scaleAnimation {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes iconLineTip {
        0% { width: 0; left: 1px; top: 19px; }
        54% { width: 0; left: 1px; top: 19px; }
        70% { width: 50px; left: -8px; top: 37px; }
        84% { width: 17px; left: 21px; top: 48px; }
        100% { width: 25px; left: 14px; top: 45px; }
    }
    @keyframes iconLineLong {
        0% { width: 0; right: 46px; top: 54px; }
        65% { width: 0; right: 46px; top: 54px; }
        84% { width: 55px; right: 0px; top: 35px; }
        100% { width: 47px; right: 8px; top: 38px; }
    }

    .conf-title {
        color: #2b3a1a;
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    
    .conf-subtitle {
        color: #555;
        font-size: 1.1rem;
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }

    .order-details-box {
        background: #fdfdfd;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 35px;
        text-align: left;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .order-details-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.03);
    }

    .order-info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        border-bottom: 1px dashed #eee;
        padding-bottom: 15px;
    }
    .order-info-row:last-child {
        margin-bottom: 0;
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-label {
        color: #888;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #333;
        font-size: 1rem;
        font-weight: 700;
        text-align: right;
    }

    .info-product {
        color: var(--primary-color, #76a713);
        font-weight: 800;
        font-size: 1.1rem;
    }

    .btn-action-group {
        display: flex;
        gap: 15px;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    .btn-dashboard {
        background: var(--primary-color, #76a713);
        color: #fff;
        border: 2px solid var(--primary-color, #76a713);
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-dashboard:hover {
        background: #5d840f;
        border-color: #5d840f;
        transform: translateY(-2px);
        color: #fff;
    }

    .btn-shop {
        background: transparent;
        color: var(--primary-color, #76a713);
        border: 2px solid var(--primary-color, #76a713);
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-shop:hover {
        background: rgba(118, 167, 19, 0.05);
        color: var(--primary-color, #76a713);
        transform: translateY(-2px);
    }
    
    @media(max-width: 576px) {
        .btn-action-group {
            flex-direction: column;
        }
        .order-info-row {
            flex-direction: column;
            gap: 8px;
        }
        .info-value {
            text-align: left;
        }
    }
</style>

<div class="order-confirmation-container">
    <div class="container d-flex justify-content-center">
        <div class="confirmation-card">
            
            <!-- Animated Checkmark -->
            <div class="success-checkmark">
                <div class="check-icon">
                    <span class="icon-line line-tip check-line"></span>
                    <span class="icon-line line-long check-line"></span>
                    <div class="icon-circle check-circle"></div>
                    <div class="icon-fix check-fix"></div>
                </div>
            </div>

            <h1 class="conf-title">Order Confirmed!</h1>
            <p class="conf-subtitle">Thank you for letting Plantsware bring nature closer to you.</p>

            <div class="order-details-box">
                <!-- <div class="order-info-row">
                    <div class="info-label">Order Number</div>
                    <div class="info-value">#{{ $order->order_number }}</div>
                </div> -->
                
                <div class="order-info-row">
                    <div class="info-label">Product(s) Ordered</div>
                    <div class="info-value info-product">
                        @if($order->items && $order->items->count() > 0)
                            {{ $order->items->first()->product_name }}
                            @if($order->items->count() > 1)
                                <span class="text-muted" style="font-size: 0.85em; font-weight: 600;">+ {{ $order->items->count() - 1 }} more</span>
                            @endif
                        @else
                            Items securely placed
                        @endif
                    </div>
                </div>

                <div class="order-info-row">
                    <div class="info-label">Order Date</div>
                    <div class="info-value">{{ $order->created_at->format('d M, Y') }}</div>
                </div>

                <div class="order-info-row">
                    <div class="info-label">Total Amount</div>
                    <div class="info-value">₹{{ number_format($order->total ?? 0, 2) }}</div>
                </div>
            </div>

            <div class="btn-action-group">
                <a href="{{ route('user.dashboard') }}#order-history" class="btn-dashboard">
                    <i class="fas fa-boxes"></i> View Details
                </a>
                <a href="{{ url('/') }}" class="btn-shop">
                    <i class="fas fa-leaf"></i> Continue Shopping
                </a>
            </div>

        </div>
    </div>
</div>

@include('view.layout.footer')