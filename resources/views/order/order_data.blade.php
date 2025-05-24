@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/order_data.js" type="module"></script>
@endpush

@push('modals-dependencies')
@include('/partials/order/order_detail_modal')
@include('/partials/order/reject_order_modal')
@include('/partials/order/transaction_proof_upload_modal')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="main-title mb-4">{{ $title }}</h1>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="ordersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->product->product_name }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    {{ $order->payment->payment_method }}
                                    @if($order->bank)
                                        <br>
                                        <small class="text-muted">{{ $order->bank->bank_name }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->status->style }}">
                                        {{ $order->status->order_status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-info view-order" 
                                            data-bs-toggle="modal" data-bs-target="#orderDetailModal"
                                            data-order-id="{{ $order->id }}">
                                            View
                                        </button>
                                        
                                        @if(auth()->user()->role_id == 1) {{-- Admin only --}}
                                            <button type="button" class="btn btn-sm btn-warning update-status" 
                                                data-bs-toggle="modal" data-bs-target="#updateStatusModal"
                                                data-order-id="{{ $order->id }}">
                                                Update Status
                                            </button>
                                        @endif

                                        @if($order->payment_id == 1 && $order->status_id == 2 && !$order->transaction_doc)
                                            <button type="button" class="btn btn-sm btn-primary upload-proof" 
                                                data-bs-toggle="modal" data-bs-target="#uploadProofModal"
                                                data-order-id="{{ $order->id }}">
                                                Upload Payment
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('partials.order.order_detail_modal')
@include('partials.order.update_status_modal')
@include('partials.order.upload_proof_modal')

@push('scripts')
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        order: [[0, 'desc']]
    });

    // View Order Details
    $('.view-order').click(function() {
        const orderId = $(this).data('order-id');
        $.get(`/order/detail/${orderId}`, function(data) {
            $('#orderDetailModal .modal-body').html(data);
        });
    });

    // Update Status
    $('.update-status').click(function() {
        const orderId = $(this).data('order-id');
        $('#updateStatusForm').attr('action', `/order/update_status/${orderId}`);
    });

    // Upload Payment Proof
    $('.upload-proof').click(function() {
        const orderId = $(this).data('order-id');
        $('#uploadProofForm').attr('action', `/order/upload_proof/${orderId}`);
    });
});
</script>
@endpush

@endsection