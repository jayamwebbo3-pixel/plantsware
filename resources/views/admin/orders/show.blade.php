@extends('admin.layout')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted">← Back to Orders</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <h4>Order #{{ $order->order_number }}</h4>
                    <span class="badge {{ $order->getStatusBadgeClass() }} fs-6">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <h5>Ordered Items</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; overflow: hidden; margin-right: 10px; vertical-align: middle;">
                                            @if($item->combo_pack_id && $item->comboPack && !$item->comboPack->is_combo_only)
                                                @php
                                                    $imgData = is_string($item->comboPack->image) ? json_decode($item->comboPack->image, true) : $item->comboPack->image;
                                                @endphp
                                                @if(is_array($imgData) && count($imgData) >= 2)
                                                    <div class="d-flex align-items-center justify-content-center w-100 h-100 p-1">
                                                        <img src="{{ asset('storage/' . $imgData[0]) }}" alt="" style="width: 40%; height: auto; object-fit: contain;">
                                                        <span style="font-size: 10px; font-weight: bold; color: #72a420; margin: 0 1px;">+</span>
                                                        <img src="{{ asset('storage/' . $imgData[1]) }}" alt="" style="width: 40%; height: auto; object-fit: contain;">
                                                    </div>
                                                @else
                                                    <img src="{{ asset('storage/' . ($imgData[0] ?? $item->product_image)) }}" style="width:100%; height:100%; object-fit:cover;">
                                                @endif
                                            @elseif($item->product_image)
                                                <img src="{{ asset('storage/' . $item->product_image) }}" style="width:100%; height:100%; object-fit:cover;">
                                            @else
                                                <img src="{{ asset('assets/images/product/product1.jpg') }}" style="width:100%; height:100%; object-fit:cover;">
                                            @endif
                                        </div>
                                        {{ $item->product_name }}
                                        @if($item->combo_pack_id)
                                            <span class="badge bg-danger ms-1">COMBO</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-end">
                        <h5>Subtotal: ₹{{ number_format($order->subtotal, 2) }}</h5>
                        <h5>Shipping: ₹{{ number_format($order->shipping, 2) }}</h5>
                        <h5>Tax: ₹{{ number_format($order->tax, 2) }}</h5>
                        <h4>Total: ₹{{ number_format($order->total, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><h5>Update Status</h5></div>
                <div class="card-body">
                    @if (in_array($order->status, ['cancelled', 'returned']))
                        <div class="alert alert-secondary border-0 mb-0 text-center">
                            This order is <strong>{{ ucfirst($order->status) }}</strong>. It is read-only.
                        </div>
                    @elseif (in_array($order->status, ['completed']))
                        <div class="alert alert-success border-0 mb-0 text-center">
                            This order is <strong>Completed</strong> and the return window is closed.
                        </div>
                    @elseif ($order->status === 'return_rejected')
                        <div class="alert alert-danger border-0 mb-0 text-center">
                            Return request was <strong>Rejected</strong>. Reason: {{ $order->return_rejection_reason ?? 'Admin rejection' }}
                        </div>
                    @elseif ($order->status === 'delivered')
                        <div class="alert alert-success border-0 mb-0 text-center">
                            This order has been <strong>Delivered</strong>.
                        </div>
                    @elseif ($order->status === 'return_requested')
                        <div class="alert alert-warning border-0 mb-2">
                            <strong>Return Requested</strong><br>
                            <small>Reason: {{ $order->return_reason ?? 'User requested a return' }}</small>
                        </div>
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" name="status" value="returned" class="btn btn-success w-100 mb-2" onclick="return confirm('Approve the return and mark as Returned?')">Approve Return</button>

                            <div class="input-group mb-2">
                                <input type="text" name="return_rejection_reason" class="form-control" placeholder="Rejection reason" required>
                                <button type="submit" name="status" value="return_rejected" class="btn btn-danger" onclick="return confirm('Reject this return request?')">Reject Return</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf @method('PATCH')

                            @if ($order->status === 'confirmed' || $order->status === 'pending')
                                <button type="submit" name="status" value="processing" class="btn btn-success w-100 mb-2">Accept Order (Processing)</button>
                            @elseif ($order->status === 'processing')
                                <button type="submit" name="status" value="shipped" class="btn btn-primary w-100 mb-2">Ship Order</button>
                            @elseif ($order->status === 'shipped')
                                <button type="submit" name="status" value="delivered" class="btn btn-success w-100 mb-2">Mark Delivered</button>
                                <button type="submit" name="status" value="returned" class="btn btn-warning w-100" onclick="return confirm('Are you sure you want to mark this order as returned?')">Return Order</button>
                            @endif
                        </form>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5>Customer & Shipping</h5></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->user?->name ?? 'Guest' }}</p>
                    <p><strong>Email:</strong> {{ $order->user?->email ?? 'N/A' }}</p>
                    <hr>
                    <p><strong>Shipping Address:</strong><br>
                        {{ $order->shipping_address['name'] ?? '' }}<br>
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['pincode'] ?? '' }}<br>
                        {{ $order->shipping_address['state'] ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection