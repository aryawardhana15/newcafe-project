@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/make_order.js"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Buat Pesanan Baru</h1>
        <p class="text-gray-600 mt-2">Silakan lengkapi detail pesanan Anda di bawah ini</p>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded-lg" 
         x-data="{ show: true }" 
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <p>{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Pemesanan -->
        <div class="lg:col-span-2">
            <form method="POST" action="/order/make_order/{{ $product->id }}" class="space-y-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="shipping_address" id="shipping_address">
                <input type="hidden" name="total_price" id="total_price" value="0">
                <input type="hidden" name="coupon_used" id="coupon_used" value="0">

                <!-- Product Info -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-blue-600 mb-6">Detail Produk</h2>
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/3">
                                <div class="relative aspect-square rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300 bg-blue-50">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->product_name }}"
                                         class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
                                </div>
                            </div>
                            <div class="w-full md:w-2/3 space-y-4 bg-blue-50 p-4 rounded-lg">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-700">{{ $product->product_name }}</h3>
                                    <div class="mt-2 text-lg font-bold text-blue-600">
                                        Rp {{ number_format($product->price * (1 - $product->discount/100)) }}
                                        @if($product->discount > 0)
                                        <span class="text-sm font-normal text-blue-400 line-through ml-2">
                                            Rp {{ number_format($product->price) }}
                                        </span>
                                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                                            -{{ $product->discount }}%
                                        </span>
                                        @endif
                                    </div>
                                    <input type="hidden" id="price" data-trueprice="{{ $product->price * (1 - $product->discount/100) }}">
                                </div>
                                <div>
                                    <p class="text-sm text-blue-500">Stok Tersedia</p>
                                    <p class="text-lg font-semibold {{ $product->stock > 10 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $product->stock }} unit
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quantity & Address -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pesanan</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        onclick="decrementQuantity()"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200">
                                    <i class="fas fa-minus text-gray-600"></i>
                                </button>
                                <input type="number" 
                                       class="w-20 text-center border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       value="{{ old('quantity', 1) }}" 
                                       required>
                                <button type="button" 
                                        onclick="incrementQuantity()"
                                        class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors duration-200">
                                    <i class="fas fa-plus text-gray-600"></i>
                                </button>
                            </div>
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                            <textarea class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required 
                                      placeholder="Masukkan alamat lengkap pengiriman">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Metode Pembayaran</h2>
                        <div class="space-y-4">
                            <!-- Bank Transfer -->
                            <div class="relative">
                                <input type="radio" 
                                       name="payment_method" 
                                       id="bank_transfer" 
                                       value="1" 
                                       {{ old('payment_method') == '1' ? 'checked' : '' }} 
                                       required
                                       class="hidden peer">
                                <label for="bank_transfer" 
                                       class="block p-4 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all duration-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-university text-2xl text-blue-500 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">Transfer Bank</div>
                                            <div class="text-sm text-gray-500">Pembayaran melalui transfer bank</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Bank Selection -->
                            <div id="bank_selection" class="ml-8 hidden">
                                <select name="bank_id" 
                                        id="bank_id"
                                        class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('bank_id') border-red-500 @enderror">
                                    <option value="">Pilih Bank</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} - {{ $bank->account_number }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- COD -->
                            <div class="relative">
                                <input type="radio" 
                                       name="payment_method" 
                                       id="cod" 
                                       value="2" 
                                       {{ old('payment_method') == '2' ? 'checked' : '' }}
                                       class="hidden peer">
                                <label for="cod" 
                                       class="block p-4 bg-white border rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all duration-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-truck text-2xl text-green-500 mr-3"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">Cash on Delivery (COD)</div>
                                            <div class="text-sm text-gray-500">Bayar saat pesanan diterima</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Pesanan</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">Rp <span id="subtotal">0</span></span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">Biaya Pengiriman</span>
                                <span class="font-medium">Rp <span id="shipping_cost">0</span></span>
                            </div>

                            @if(auth()->user()->coupon > 0)
                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-gray-600">Kupon Tersedia</span>
                                        <span class="ml-2 bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                                            {{ auth()->user()->coupon }}
                                        </span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="use_coupon" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ml-2 text-sm font-medium text-gray-600">Gunakan Kupon</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">*Potongan 10% dari subtotal</p>
                            </div>
                            @endif

                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-blue-600">Rp <span id="total">0</span></span>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white rounded-lg px-4 py-3 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Buat Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Pesanan Aktif -->
        
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bankTransfer = document.getElementById('bank_transfer');
    const cod = document.getElementById('cod');
    const bankSelection = document.getElementById('bank_selection');
    const bankId = document.getElementById('bank_id');
    const addressInput = document.getElementById('address');
    const shippingAddressInput = document.getElementById('shipping_address');

    function toggleBankSelection() {
        if (bankTransfer.checked) {
            bankSelection.classList.remove('hidden');
            bankId.required = true;
        } else {
            bankSelection.classList.add('hidden');
            bankId.required = false;
            bankId.value = '';
        }
    }

    function updateShippingAddress() {
        shippingAddressInput.value = addressInput.value;
    }

    bankTransfer.addEventListener('change', toggleBankSelection);
    cod.addEventListener('change', toggleBankSelection);
    addressInput.addEventListener('input', updateShippingAddress);

    // Initial state
    toggleBankSelection();
    updateShippingAddress();
});

function calculateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const price = parseFloat(document.getElementById('price').dataset.trueprice);
    const useCoupon = document.getElementById('use_coupon')?.checked || false;
    
    let subtotal = quantity * price;
    const shippingCost = 10000; // Biaya pengiriman tetap
    
    if (useCoupon) {
        subtotal = subtotal * 0.9; // Potongan 10%
        document.getElementById('coupon_used').value = '1';
    } else {
        document.getElementById('coupon_used').value = '0';
    }
    
    const total = subtotal + shippingCost;
    
    document.getElementById('subtotal').textContent = numberFormat(subtotal);
    document.getElementById('shipping_cost').textContent = numberFormat(shippingCost);
    document.getElementById('total').textContent = numberFormat(total);
    document.getElementById('total_price').value = total;
}

function incrementQuantity() {
    const qty = document.getElementById('quantity');
    const max = parseInt(qty.max);
    const current = parseInt(qty.value);
    if (current < max) {
        qty.value = current + 1;
        calculateTotal();
    }
}

function decrementQuantity() {
    const qty = document.getElementById('quantity');
    const current = parseInt(qty.value);
    if (current > 1) {
        qty.value = current - 1;
        calculateTotal();
    }
}

function numberFormat(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Event listeners
document.getElementById('quantity').addEventListener('change', calculateTotal);
document.getElementById('use_coupon')?.addEventListener('change', calculateTotal);

// Initial calculation
calculateTotal();
</script>
@endpush

@endsection