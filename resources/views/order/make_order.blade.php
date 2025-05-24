@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/make_order.js"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="main-title mb-4">Buat Pesanan Baru</h1>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="/order/make_order/{{ $product->id }}" method="post" id="orderForm">
        @csrf
        <div class="row">
            <!-- Product Details -->
            <div class="col-lg-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Detail Produk</h5>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="shipping_address" id="shipping_address">
                        <input type="hidden" name="total_price" id="total_price" value="0">
                        <input type="hidden" name="coupon_used" id="coupon_used" value="0">
                        
                        <!-- Product Info -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" 
                                    class="img-fluid rounded">
                            </div>
                            <div class="col-md-9">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" value="{{ $product->product_name }}" disabled>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Harga</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control" 
                                                    value="{{ number_format($product->price * (1 - $product->discount/100)) }}" disabled>
                                                <input type="hidden" id="price" 
                                                    data-trueprice="{{ $product->price * (1 - $product->discount/100) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Stok Tersedia</label>
                                            <input type="text" class="form-control" value="{{ $product->stock }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group mb-4">
                            <label class="form-label">Jumlah Pesanan</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">-</button>
                                <input type="number" class="form-control text-center @error('quantity') is-invalid @enderror" 
                                    id="quantity" name="quantity" min="1" max="{{ $product->stock }}" 
                                    value="{{ old('quantity', 1) }}" required>
                                <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">+</button>
                            </div>
                            @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required 
                                placeholder="Masukkan alamat lengkap pengiriman">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Metode Pembayaran</h5>
                        
                        <div class="payment-methods">
                            <!-- Bank Transfer -->
                            <div class="form-check custom-radio mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" 
                                    value="1" {{ old('payment_method') == '1' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="fas fa-university me-2"></i>Transfer Bank
                                </label>
                            </div>

                            <!-- Bank Selection -->
                            <div id="bank_selection" class="mb-4 ms-4" style="display: none;">
                                <select class="form-select @error('bank_id') is-invalid @enderror" name="bank_id" id="bank_id">
                                    <option value="">Pilih Bank</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} - {{ $bank->account_number }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- COD -->
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" 
                                    value="2" {{ old('payment_method') == '2' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cod">
                                    <i class="fas fa-truck me-2"></i>Cash on Delivery (COD)
                                </label>
                            </div>
                        </div>

                        @error('payment_method')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Ringkasan Pesanan</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp <span id="subtotal">0</span></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Biaya Pengiriman:</span>
                            <span>Rp <span id="shipping_cost">0</span></span>
                        </div>

                        @if(auth()->user()->coupon > 0)
                        <div class="coupon-section mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    Kupon Tersedia:
                                    <span class="badge bg-success">{{ auth()->user()->coupon }}</span>
                                </span>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="use_coupon">
                                    <label class="form-check-label" for="use_coupon">Gunakan Kupon</label>
                                </div>
                            </div>
                            <small class="text-muted">*Potongan 10% dari subtotal</small>
                        </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong>Rp <span id="total">0</span></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function incrementQuantity() {
    const qty = document.getElementById('quantity');
    const max = parseInt(qty.max);
    const current = parseInt(qty.value);
    if (current < max) {
        qty.value = current + 1;
        qty.dispatchEvent(new Event('change'));
    }
}

function decrementQuantity() {
    const qty = document.getElementById('quantity');
    const current = parseInt(qty.value);
    if (current > 1) {
        qty.value = current - 1;
        qty.dispatchEvent(new Event('change'));
    }
}
</script>
@endpush

@endsection