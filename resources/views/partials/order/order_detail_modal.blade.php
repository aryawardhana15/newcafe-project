<!-- Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Product Info -->
                <div class="mb-4">
                    <h6 class="font-semibold mb-3">Informasi Produk</h6>
                    <div class="flex items-center space-x-4">
                        <img id="image_product_detail" class="w-20 h-20 rounded-lg object-cover" src="" alt="Product Image">
                        <div>
                            <h4 id="product_name_detail" class="text-lg font-medium"></h4>
                            <p id="quantity_detail" class="text-gray-600"></p>
                            <p id="price_detail" class="text-gray-600"></p>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="mb-4">
                    <h6 class="font-semibold mb-3">Informasi Pelanggan</h6>
                    <p><span class="text-gray-600">Nama:</span> <span id="username_detail"></span></p>
                    <p><span class="text-gray-600">Alamat:</span> <span id="address_detail"></span></p>
                    <p><span class="text-gray-600">Alamat Pengiriman:</span> <span id="shipping_address_detail"></span></p>
                </div>

                <!-- Payment Info -->
                <div class="mb-4">
                    <h6 class="font-semibold mb-3">Informasi Pembayaran</h6>
                    <p><span class="text-gray-600">Metode Pembayaran:</span> <span id="payment_method_detail"></span></p>
                    <p id="bank_detail_container" class="hidden">
                        <span class="text-gray-600">Bank:</span> <span id="bank_detail"></span>
                    </p>
                    <p><span class="text-gray-600">Total Pembayaran:</span> <span id="total_price_detail"></span></p>
                    <div id="proof_container" class="hidden mt-3">
                        <p class="text-gray-600 mb-2">Bukti Pembayaran:</p>
                        <img id="transaction_doc_detail" class="max-w-xs rounded-lg" src="" alt="Bukti Pembayaran">
                    </div>
                </div>

                <!-- Order Status -->
                <div>
                    <h6 class="font-semibold mb-3">Status Pesanan</h6>
                    <div class="flex items-center space-x-2">
                        <span id="status_detail" class="px-2 py-1 rounded-full text-sm font-medium"></span>
                        <span id="order_date_detail" class="text-gray-600"></span>
                    </div>
                    <p id="refusal_reason_container" class="hidden mt-2">
                        <span class="text-gray-600">Alasan Penolakan:</span>
                        <span id="refusal_reason_detail" class="text-red-600"></span>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
            <div class="modal-header border-b bg-gray-50 p-4">
                <h5 class="modal-title text-lg font-semibold text-gray-800" id="orderDetailModalLabel">
                    Detail Pesanan
                </h5>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-6">
                <div class="space-y-6">
                    <!-- Product Info -->
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <img id="image_product_detail" class="w-24 h-24 rounded-lg object-cover" src="" alt="Product Image">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 id="product_name_detail" class="text-lg font-medium text-gray-900"></h4>
                            <div class="mt-1 flex items-center">
                                <span class="text-sm text-gray-500">Order by</span>
                                <span id="username_detail" class="ml-1 text-sm font-medium text-gray-900"></span>
                            </div>
                            <div class="mt-1">
                                <span class="text-sm text-gray-500">Order Date:</span>
                                <span id="order_date_detail" class="ml-1 text-sm text-gray-900"></span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span id="status_detail" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Quantity</h5>
                            <p id="quantiity_detail" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Total Price</h5>
                            <p id="total_price_detail" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div>
                            <h5 class="text-sm font-medium text-gray-500">Payment Method</h5>
                            <p id="payment_method_detail" class="mt-1 text-sm text-gray-900"></p>
                        </div>
                        <div id="bank_info" class="hidden">
                            <h5 class="text-sm font-medium text-gray-500">Bank Info</h5>
                            <p class="mt-1 text-sm text-gray-900">
                                <span id="bank_detail"></span>
                                <span id="account_number_detail" class="block text-gray-500"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-500">Shipping Address</h5>
                        <p id="address_detail" class="mt-1 text-sm text-gray-900"></p>
                    </div>

                    <!-- Payment Proof -->
                    <div id="payment_proof_section">
                        <h5 class="text-sm font-medium text-gray-500">Payment Proof</h5>
                        <div class="mt-2">
                            <img id="transaction_doc_detail" class="max-w-xs rounded-lg shadow-sm" src="" alt="Payment Proof">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-500">Notes</h5>
                        <p id="notes_transaction_detail" class="mt-1 text-sm text-gray-900"></p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3">
                        @if(auth()->user()->role_id == 2)
                            <form id="form_cancel_order" method="POST">
                                @csrf
                                <button type="submit" id="button_cancel_order"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel Order
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->role_id == 1)
                            <form id="form_reject_order" method="POST">
                                @csrf
                                <button type="submit" id="button_reject_order"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    <i class="fas fa-ban mr-2"></i>
                                    Reject Order
                                </button>
                            </form>

                            <form id="form_approve_order" method="POST">
                                @csrf
                                <button type="submit" id="button_approve_order"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-check mr-2"></i>
                                    Approve Order
                                </button>
                            </form>

                            <form id="form_end_order" method="POST">
                                @csrf
                                <button type="submit" id="button_end_order"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-flag-checkered mr-2"></i>
                                    Complete Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Show/hide bank info based on payment method
function toggleBankInfo(paymentMethod) {
    const bankInfo = document.getElementById('bank_info');
    if (paymentMethod === 'Transfer Bank') {
        bankInfo.classList.remove('hidden');
    } else {
        bankInfo.classList.add('hidden');
    }
}

// Update status badge style
function updateStatusBadge(status, style) {
    const badge = document.getElementById('status_detail');
    badge.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium`;
    
    switch(style) {
        case 'success':
            badge.classList.add('bg-green-100', 'text-green-800');
            break;
        case 'warning':
            badge.classList.add('bg-yellow-100', 'text-yellow-800');
            break;
        case 'danger':
            badge.classList.add('bg-red-100', 'text-red-800');
            break;
        default:
            badge.classList.add('bg-gray-100', 'text-gray-800');
    }
    
    badge.textContent = status;
}

// Show/hide payment proof section
function togglePaymentProof(paymentMethod, transactionDoc) {
    const proofSection = document.getElementById('payment_proof_section');
    if (paymentMethod === 'Transfer Bank' && transactionDoc) {
        proofSection.classList.remove('hidden');
    } else {
        proofSection.classList.add('hidden');
    }
}
</script>
@endpush