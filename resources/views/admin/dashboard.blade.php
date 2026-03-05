@extends('admin.layout')

@section('title', 'Dashboard Overview')

@section('content')
<style>
    /* Premium Modern Dashboard Styles */
    :root {
        --dash-primary: #79d2b2;
        --dash-primary-light: #e6f6f2;
        --dash-success: #10b981;
        --dash-info: #0284c7;
        --dash-warning: #f59e0b;
        --dash-bg: #f8fafb;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-border: #e2e8f0;
    }

    body {
        background-color: var(--dash-bg);
    }

    .modern-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--card-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 2px 4px -1px rgba(0,0,0,0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stat-title {
        color: var(--text-muted);
        font-size: 0.95rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        margin-bottom: 4px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-revenue { background: #e0f2fe; color: #0284c7; }
    .icon-orders { background: #e6f6f2; color: #3ca884; }
    .icon-customers { background: #fef3c7; color: #d97706; }
    .icon-pending { background: #fee2e2; color: #ef4444; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-trend {
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trend-up { color: var(--dash-success); }
    .trend-down { color: #f43f5e; }

    /* Chart Section */
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    /* Top Products Container */
    .product-list {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 15px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    
    .product-list::-webkit-scrollbar { height: 6px; }
    .product-list::-webkit-scrollbar-track { background: transparent; }
    .product-list::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }

    .product-card {
        min-width: 150px;
        background: var(--dash-bg);
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        flex-shrink: 0;
        transition: border-color 0.2s;
    }
    
    .product-card:hover {
        border-color: var(--dash-primary);
    }

    .product-img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 12px;
        border-radius: 8px;
        background: #fff;
    }

    .product-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
    }

    .product-sales {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }
</style>

<div class="row g-4 mb-4">
    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Revenue</div>
                </div>
                <div class="stat-icon icon-revenue">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <h3 class="stat-value">₹{{ number_format($stats['revenue'], 2) }}</h3>
            <div class="stat-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>Overall Success</span>
            </div>
        </div>
    </div>
    
    <!-- Total Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Orders</div>
                </div>
                <div class="stat-icon icon-orders">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
            <h3 class="stat-value">{{ number_format($stats['total_orders']) }}</h3>
            <div class="stat-trend trend-up">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>All operations</span>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Customers</div>
                </div>
                <div class="stat-icon icon-customers">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <h3 class="stat-value">{{ number_format($stats['total_customers']) }}</h3>
            <div class="stat-trend trend-up text-primary">
                <i class="fa-solid fa-user-plus"></i>
                <span>New vs Return</span>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="modern-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Pending Orders</div>
                </div>
                <div class="stat-icon icon-pending">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <h3 class="stat-value">{{ number_format($stats['pending_orders']) }}</h3>
            <div class="stat-trend trend-down">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>Needs action</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Sales Analytic Chart Area -->
    <div class="col-md-12">
        <div class="modern-card">
            <div class="chart-header">
                <div class="chart-title">Sales Analytic (Last 7 Days)</div>
            </div>
            
            <div style="height: 300px; width: 100%;">
                <canvas id="salesAnalyticChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-12">
        <div class="modern-card">
            <div class="chart-header">
                <div class="chart-title">Top Selling Products</div>
            </div>
            
            <div class="product-list">
                @forelse($top_products as $product)
                <div class="product-card">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                    @else
                        <img src="https://via.placeholder.com/100?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="product-img">
                    @endif
                    <div class="product-name" title="{{ $product->name }}">{{ $product->name }}</div>
                    <div class="product-sales text-success">₹{{ number_format($product->price, 2) }}</div>
                </div>
                @empty
                    <div class="text-center text-muted w-100 py-3">No top selling products available yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxLine = document.getElementById('salesAnalyticChart').getContext('2d');
        
        let gradient = ctxLine.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(60, 168, 132, 0.5)');
        gradient.addColorStop(1, 'rgba(60, 168, 132, 0.05)');

        // Retrieve PHP arrays injected into Blade
        const labels = {!! json_encode($chart_labels) !!};
        const dataStats = {!! json_encode($chart_data) !!};

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: dataStats,
                    borderColor: '#3ca884',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, // Smooth curve
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3ca884',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, family: "'Inter', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                return '₹ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: "'Inter', sans-serif" } }
                    },
                    y: {
                        border: { display: false },
                        grid: { color: '#f1f5f9', borderDash: [5, 5] },
                        ticks: { 
                            color: '#94a3b8', 
                            font: { family: "'Inter', sans-serif" },
                            callback: function(value) { return '₹ ' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
