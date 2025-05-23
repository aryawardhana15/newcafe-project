@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/order.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/make_order.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
@endpush

@push('modals-dependencies')
@include('/partials/order/transfer_instructions_modal')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="main-title mb-4">{{ $title }}</h1>

    <form action="/order/make_order/{{ $product->id }}" method="post" id="orderForm">
        @csrf
        <div class="row">
            <!-- Product Details -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Product Details</h5>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="shipping_address" id="shipping_address">
                        <input type="hidden" name="total_price" id="total_price" value="0">
                        <input type="hidden" name="coupon_used" id="coupon_used" value="0">
                        
                        <!-- Product Info -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" value="{{ $product->product_name }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control" value="Rp. {{ number_format($product->price * (1 - $product->discount/100)) }}" disabled>
                                    <input type="hidden" id="price" data-trueprice="{{ $product->price * (1 - $product->discount/100) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="form-group mb-3">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                id="quantity" name="quantity" min="1" max="{{ $product->stock }}" 
                                value="{{ old('quantity', 1) }}" required>
                            @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group mb-3">
                            <label for="address">Delivery Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" rows="3" required>{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Payment Method</h5>
                        
                        <!-- Bank Transfer -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" 
                                value="1" {{ old('payment_method') == '1' ? 'checked' : '' }} required>
                            <label class="form-check-label" for="bank_transfer">
                                Bank Transfer
                            </label>
                        </div>

                        <!-- Bank Selection -->
                        <div id="bank_selection" class="mb-3" style="display: none;">
                            <select class="form-select @error('bank_id') is-invalid @enderror" name="bank_id" id="bank_id">
                                <option value="">Select Bank</option>
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
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" 
                                value="2" {{ old('payment_method') == '2' ? 'checked' : '' }}>
                            <label class="form-check-label" for="cod">
                                Cash on Delivery (COD)
                            </label>
                        </div>
                        @error('payment_method')
                        <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp. <span id="subtotal">0</span></span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Rp. <span id="shipping_cost">0</span></span>
                        </div>

                        @if(auth()->user()->coupon > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                Coupon Available:
                                <span class="text-success">{{ auth()->user()->coupon }}</span>
                            </span>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="use_coupon">
                                <label class="form-check-label" for="use_coupon">Use Coupon</label>
                            </div>
                        </div>
                        @endif

                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total:</strong>
                            <strong>Rp. <span id="total">0</span></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method handling
    const bankTransfer = document.getElementById('bank_transfer');
    const cod = document.getElementById('cod');
    const bankSelection = document.getElementById('bank_selection');
    const bankId = document.getElementById('bank_id');

    function toggleBankSelection() {
        bankSelection.style.display = bankTransfer.checked ? 'block' : 'none';
        bankId.required = bankTransfer.checked;
    }

    bankTransfer.addEventListener('change', toggleBankSelection);
    cod.addEventListener('change', toggleBankSelection);
    
    // Initial state
    toggleBankSelection();

    // Calculate total
    const quantity = document.getElementById('quantity');
    const price = document.getElementById('price');
    const address = document.getElementById('address');
    const useCoupon = document.getElementById('use_coupon');

    function calculateTotal() {
        const qty = parseInt(quantity.value) || 0;
        const basePrice = parseFloat(price.dataset.trueprice) || 0;
        const shippingCost = qty > 0 ? 10000 : 0;
        
        let subtotal = qty * basePrice;
        
        // Apply coupon discount if checked
        if (useCoupon && useCoupon.checked) {
            subtotal = subtotal * 0.9; // 10% discount
            document.getElementById('coupon_used').value = 1;
        } else {
            document.getElementById('coupon_used').value = 0;
        }

        const total = subtotal + shippingCost;

        document.getElementById('subtotal').textContent = Math.round(subtotal).toLocaleString();
        document.getElementById('shipping_cost').textContent = shippingCost.toLocaleString();
        document.getElementById('total').textContent = Math.round(total).toLocaleString();
        document.getElementById('total_price').value = Math.round(total);
        
        // Update shipping address
        document.getElementById('shipping_address').value = address.value;
    }

    // Event listeners
    quantity.addEventListener('change', calculateTotal);
    quantity.addEventListener('input', calculateTotal);
    address.addEventListener('input', calculateTotal);
    if (useCoupon) {
        useCoupon.addEventListener('change', calculateTotal);
    }

    // Form validation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        if (!bankTransfer.checked && !cod.checked) {
            e.preventDefault();
            alert('Please select a payment method');
            return false;
        }
        
        if (bankTransfer.checked && !bankId.value) {
            e.preventDefault();
            alert('Please select a bank');
            return false;
        }
        
        return true;
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endpush