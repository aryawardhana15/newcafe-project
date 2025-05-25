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
<div class="container-fluid px-4" x-data="orderData">
    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
            
            <!-- Search and Filter -->
                <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                        x-model="searchQuery" 
                        placeholder="Cari pesanan..." 
                        class="w-64 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
                
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="mr-2">Filter Status</span>
                        <i class="fas fa-chevron-down"></i>
                        </button>
                    
                        <div x-show="open" 
                             @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                        <a href="#" @click.prevent="selectedStatus = 'all'; open = false"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Semua Status
                            </a>
                            @foreach($status as $s)
                        <a href="#" @click.prevent="selectedStatus = '{{ $s->id }}'; open = false"
                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                {{ $s->order_status }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        <!-- Active Orders Section -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesanan Aktif</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($activeOrders as $order)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('storage/' . $order->product->image) }}" 
                                 alt="{{ $order->product->product_name }}"
                                 class="w-16 h-16 rounded-lg object-cover">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $order->product->product_name }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->quantity }} unit</p>
                                <p class="font-semibold text-gray-800 mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($order->status->style == 'success') bg-green-100 text-green-800
                                    @elseif($order->status->style == 'warning') bg-yellow-100 text-yellow-800
                                    @elseif($order->status->style == 'danger') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $order->status->order_status }}
                                </span>
                            </div>
                            
                            <!-- Payment Method Info -->
                            <div class="mt-2">
                                <span class="text-sm font-medium text-gray-600">Metode Pembayaran:</span>
                                <span class="text-sm text-gray-800">{{ $order->payment->payment_method }}</span>
                                </div>

                            <!-- Bank Transfer Details -->
                            @if($order->payment_id == 1 && $order->bank)
                                <div class="mt-2 p-3 bg-white rounded-lg border border-gray-200">
                                    <p class="text-sm font-medium text-gray-800">Informasi Transfer:</p>
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-600">Bank: {{ $order->bank->bank_name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm text-gray-600">No. Rekening:</p>
                                            <div class="relative flex items-center">
                                                <span class="text-sm font-medium">{{ $order->bank->account_number }}</span>
                                                <button @click="copyToClipboard('{{ $order->bank->account_number }}', {{ $order->bank->id }})"
                                                        class="ml-2 text-blue-600 hover:text-blue-800">
                                                    <i class="fas" :class="copySuccess[{{ $order->bank->id }}] ? 'fa-check' : 'fa-copy'"></i>
                                                </button>
                                </div>
                                </div>
                                        <p class="text-sm text-gray-600">Atas Nama: {{ $order->bank->account_name }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm font-medium text-gray-800">Total Transfer:</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-lg font-bold text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                            <button @click="copyToClipboard('{{ $order->total_price }}', 'price_{{ $order->id }}')"
                                                    class="text-blue-600 hover:text-blue-800">
                                                <i class="fas" :class="copySuccess['price_{{ $order->id }}'] ? 'fa-check' : 'fa-copy'"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Payment Status and Actions -->
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        @if(!$order->transaction_doc || $order->transaction_doc == env('IMAGE_PROOF'))
                                            <button @click="showUploadProof({{ $order->id }})"
                                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                                                <i class="fas fa-upload mr-2"></i>
                                                Upload Bukti Pembayaran
                                            </button>
                                        @else
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-green-600">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        Bukti pembayaran telah diupload
                                                    </span>
                                                    <button @click="viewOrderDetail({{ $order->id }})"
                                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                                        Lihat Bukti
                                                    </button>
                                </div>
                                                
                                                @if(auth()->user()->role_id == 1) {{-- Admin Role --}}
                                                    @if($order->status_id == 2) {{-- Pending Status --}}
                                                        <div class="flex space-x-2">
                                                            <form action="{{ route('order.approve', $order->id) }}" method="POST" class="flex-1">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="w-full px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 transition-colors text-sm">
                                                                    <i class="fas fa-check mr-1"></i>
                                                                    Terima Pembayaran
                                                                </button>
                                                            </form>
                                                            <button @click="showRejectModal({{ $order->id }})"
                                                                    class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                                                                <i class="fas fa-times mr-1"></i>
                                                                Tolak
                                                            </button>
                                    </div>
                                                    @endif
                                                @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button @click="viewOrderDetail({{ $order->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-4">
                        <i class="fas fa-shopping-bag text-gray-300 text-3xl mb-2"></i>
                        <p class="text-gray-500">Belum ada pesanan aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- All Orders Section -->
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Semua Pesanan</h2>
            @if($orders->isEmpty())
                <div class="text-center py-8">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart text-gray-300 text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-600 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500">Silakan pesan produk terlebih dahulu</p>
                    <a href="/product" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Produk
                    </a>
                </div>
            @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($orders as $order)
                <div x-show="matchesFilters('{{ $order->id }}', '{{ $order->status_id }}')"
                     class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $order->product->image) }}" 
                                         alt="{{ $order->product->product_name }}"
                                         class="w-16 h-16 rounded-lg object-cover">
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $order->product->product_name }}</h3>
                                    <p class="text-gray-600">Order #{{ $order->id }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status->style == 'success') bg-green-100 text-green-800
                                    @elseif($order->status->style == 'warning') bg-yellow-100 text-yellow-800
                                    @elseif($order->status->style == 'danger') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $order->status->order_status }}
                                </span>
                                <p class="mt-2 text-lg font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-600">{{ $order->quantity }} unit</span>
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-600">{{ $order->payment->payment_method }}</span>
                                @if($order->bank)
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-600">{{ $order->bank->bank_name }}</span>
                                @endif
                            </div>
                            
                                <div class="flex space-x-2">
                                <button @click="viewOrderDetail({{ $order->id }})"
                                        class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-2"></i>Detail
                                    </button>
                                    
                                @if($order->payment_id == 1 && $order->status_id == 2)
                                <button @click="showUploadProof({{ $order->id }})"
                                        class="px-4 py-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Upload Bukti
                                    </button>
                                    @endif

                                @if($order->status_id == 2)
                                <a href="{{ route('order.edit', $order->id) }}"
                                   class="px-4 py-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </a>
                                
                                <form action="{{ route('order.cancel', $order->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-times mr-2"></i>Batal
                                    </button>
                                </form>
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@include('partials.order.modals')

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('orderData', () => ({
        searchQuery: '',
        selectedStatus: 'all',
        copySuccess: {},
        
        matchesFilters(orderId, statusId) {
            const matchesSearch = orderId.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesStatus = this.selectedStatus === 'all' || this.selectedStatus === statusId;
            return matchesSearch && matchesStatus;
        },
        
        async copyToClipboard(text, bankId) {
            try {
                await navigator.clipboard.writeText(text);
                this.copySuccess[bankId] = true;
                setTimeout(() => {
                    this.copySuccess[bankId] = false;
                }, 2000);
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        },
        
        showRejectModal(orderId) {
            // Set order ID di form reject
            document.getElementById('rejectOrderForm').action = `/order/reject/${orderId}`;
            // Reset form
            document.getElementById('refusalReason').value = '';
            // Show modal
            new bootstrap.Modal(document.getElementById('rejectOrderModal')).show();
        },
        
        viewOrderDetail(orderId) {
            $.get(`/order/detail/${orderId}`, function(response) {
                if (response.success) {
                    $('#orderDetailModal').modal('show');
                    $('#product_name_detail').text(response.data.product.product_name);
                    $('#quantity_detail').text(`${response.data.quantity} unit`);
                    $('#price_detail').text(`Rp ${response.data.total_price.toLocaleString()}`);
                    $('#username_detail').text(response.data.user.name);
                    $('#address_detail').text(response.data.address);
                    $('#shipping_address_detail').text(response.data.shipping_address);
                    $('#payment_method_detail').text(response.data.payment.payment_method);
                    $('#total_price_detail').text(`Rp ${response.data.total_price.toLocaleString()}`);
                    $('#status_detail').text(response.data.status.order_status);
                    $('#order_date_detail').text(new Date(response.data.created_at).toLocaleString());
                    
                    if (response.data.payment_id === 1) {
                        $('#bank_detail_container').removeClass('hidden');
                        $('#bank_detail').text(response.data.bank.bank_name);
                    } else {
                        $('#bank_detail_container').addClass('hidden');
                    }
                    
                    if (response.data.transaction_doc) {
                        $('#proof_container').removeClass('hidden');
                        $('#transaction_doc_detail').attr('src', `/storage/${response.data.transaction_doc}`);
                } else {
                        $('#proof_container').addClass('hidden');
                    }
                    
                    if (response.data.refusal_reason) {
                        $('#refusal_reason_container').removeClass('hidden');
                        $('#refusal_reason_detail').text(response.data.refusal_reason);
                    } else {
                        $('#refusal_reason_container').addClass('hidden');
                    }
                } else {
                    alert(response.message);
                }
            });
        },
        
        showUploadProof(orderId) {
            $('#uploadProofModal').modal('show');
            $('#uploadProofForm').attr('action', `/order/upload_proof/${orderId}`);
        }
    }));
});
</script>
@endpush

@endsection