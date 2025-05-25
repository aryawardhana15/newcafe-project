@extends('/layouts/main')

@push('css-dependencies')
<link rel="stylesheet" type="text/css" href="/css/product.css" />
@endpush

@push('scripts-dependencies')
<script src="/js/product.js" type="module"></script>
@endpush

@push('modals-dependencies')
@include('/partials/product/product_detail_modal')
<!-- Add delete confirmation modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@section('content')
<!-- product -->
<section id="product" class="pb-5">
    <div class="container">

        @if(session()->has('message'))
        {!! session("message") !!}
        @endif

        <h5 class="section-title h1">Our Product</h5>
        @can('add_product',App\Models\Product::class)
        <div class="d-flex align-items-end flex-column mb-4">
            <a style="text-decoration: none;" href="/product/add_product">
                <div class="text-right button-kemren mr-lg-5 mr-sm-3">Add Product</div>
            </a>
        </div>
        @else
        <div class="mb-5"></div>
        @endcan

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="relative">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->product_name }}" 
                         class="w-full h-48 object-cover">
                    @if($product->discount > 0)
                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-sm font-semibold">
                        -{{ $product->discount }}%
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->product_name }}</h3>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-lg font-bold text-blue-600">
                                Rp {{ number_format($product->price * (1 - $product->discount/100)) }}
                            </p>
                            @if($product->discount > 0)
                            <p class="text-sm text-gray-500 line-through">
                                Rp {{ number_format($product->price) }}
                            </p>
                            @endif
                        </div>
                        <div class="text-sm {{ $product->stock > 10 ? 'text-green-600' : 'text-orange-600' }}">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        @if($product->stock > 0)
                        <a href="/order/make_order/{{ $product->id }}" 
                           class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Beli
                        </a>
                        @else
                        <button disabled 
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">
                            <i class="fas fa-ban mr-2"></i>
                            Stok Habis
                        </button>
                        @endif
                        
                        <button onclick="viewProductDetail({{ $product->id }})"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- product -->
<script>

function confirmDelete(button) {
    if (confirm("Yakin ingin menghapus?")) {
        button.closest('form').submit(); // ✔️ Submit form dengan method DELETE
    }
}


</script>


@endsection