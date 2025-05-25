@props(['order'])

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div class="p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="px-3 py-1 text-xs font-semibold rounded-full 
                @if($order->status->style == 'success') bg-green-100 text-green-800
                @elseif($order->status->style == 'warning') bg-yellow-100 text-yellow-800
                @elseif($order->status->style == 'danger') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $order->status->order_status }}
            </span>
        </div>

        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $order->product->image) }}" 
                     alt="{{ $order->product->product_name }}"
                     class="w-24 h-24 rounded-lg object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-base font-medium text-gray-900">{{ $order->product->product_name }}</h4>
                <div class="mt-1 flex items-center text-sm text-gray-500">
                    <i class="fas fa-box mr-2"></i>
                    <span>{{ $order->quantity }} unit</span>
                </div>
                <div class="mt-1 flex items-center text-sm text-gray-500">
                    <i class="fas fa-money-bill mr-2"></i>
                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="mt-1 flex items-center text-sm text-gray-500">
                    <i class="fas fa-credit-card mr-2"></i>
                    <span>{{ $order->payment->payment_method }}</span>
                    @if($order->bank)
                    <span class="mx-1">-</span>
                    <span>{{ $order->bank->bank_name }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-4 border-t pt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <!-- View Detail -->
                    <button type="button" 
                            onclick="viewOrderDetail({{ $order->id }})"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-eye mr-2"></i>
                        Detail
                    </button>

                    @if($order->payment_id == 1 && $order->status_id == 2 && !$order->transaction_doc)
                    <!-- Upload Proof -->
                    <button type="button"
                            onclick="showUploadProof({{ $order->id }})"
                            class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Bukti
                    </button>
                    @endif

                    @if($order->status_id == 2)
                    <!-- Cancel Order -->
                    <button type="button"
                            onclick="cancelOrder({{ $order->id }})"
                            class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-times mr-2"></i>
                        Batalkan
                    </button>
                    @endif
                </div>

                @if($order->status_id == 1)
                <!-- Order Tracking -->
                <div class="text-sm text-gray-500">
                    <i class="fas fa-truck mr-2"></i>
                    Pesanan sedang diproses
                </div>
                @endif
            </div>
        </div>
    </div>
</div> 