@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Wishlist</h1>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        {{ session('error') }}
    </div>
    @endif

    @if($wishlists->isEmpty())
    <div class="text-center py-8">
        <p class="text-gray-500 text-lg">Your wishlist is empty</p>
        <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-[#d4a037] text-white px-6 py-2 rounded-lg hover:bg-[#b8862f]">
            Browse Products
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($wishlists as $wishlist)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($wishlist->product->image)
            <img src="{{ asset('storage/products/' . $wishlist->product->image) }}" 
                 alt="{{ $wishlist->product->product_name }}"
                 class="w-full h-48 object-cover">
            @endif
            
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-2">{{ $wishlist->product->product_name }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($wishlist->product->description, 100) }}</p>
                
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-[#d4a037]">
                        Rp {{ number_format($wishlist->product->price, 0, ',', '.') }}
                    </span>
                    
                    <div class="space-x-2">
                        <a href="{{ route('order.make', $wishlist->product) }}" 
                           class="inline-block bg-[#d4a037] text-white px-4 py-2 rounded hover:bg-[#b8862f]">
                            Order Now
                        </a>
                        
                        <button onclick="removeFromWishlist({{ $wishlist->id }})"
                                class="inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
<script>
function removeFromWishlist(wishlistId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        fetch(`/wishlist/${wishlistId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to remove item from wishlist');
        });
    }
}
</script>
@endpush
@endsection 