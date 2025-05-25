@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Process Payment</h2>
                    <p class="text-gray-600 mb-8">Please complete your payment for order #{{ $order->id }}</p>
                    
                    <!-- Order Summary -->
                    <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Product:</span>
                                <span class="text-gray-900 font-medium">{{ $order->product->product_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="text-gray-900 font-medium">{{ $order->quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="text-gray-900 font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <button id="pay-button" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-credit-card mr-2"></i>
                        Pay Now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $client_key }}"></script>
<script>
const payButton = document.querySelector('#pay-button');
const snapToken = '{{ $snap_token }}';

payButton.addEventListener('click', function(e) {
    e.preventDefault();

    snap.pay(snapToken, {
        onSuccess: function(result) {
            window.location.href = '{{ route("payment.finish") }}?order_id=' + result.order_id;
        },
        onPending: function(result) {
            window.location.href = '{{ route("payment.pending") }}?order_id=' + result.order_id;
        },
        onError: function(result) {
            window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id;
        },
        onClose: function() {
            Swal.fire({
                title: 'Payment Cancelled',
                text: 'Are you sure you want to cancel the payment?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel payment',
                cancelButtonText: 'No, continue payment',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("order.show", $order->id) }}';
                } else {
                    snap.pay(snapToken);
                }
            });
        }
    });
});
</script>
@endpush

@endsection 