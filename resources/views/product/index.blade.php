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

        <div class="row justify-content-center">
            @foreach($product as $row)
            <!-- Product card -->
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="image-flip" ontouchstart="this.classList.toggle('hover');">
                    <div class="mainflip">
                        <div class="frontside">
                            <div class="card">
                                <div class="card-body text-center">
                                    <p><img class=" img-fluid" src="{{ asset('storage/' . $row->image) }}"
                                          alt="Product Name"></p>
                                    <h4 class="card-title">{{ $row->product_name }}</h4>
                                    <p class="card-text">{{ $row->orientation }}</p>
                                    <div class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="backside">
                            <div class="card">
                                <div class="card-body text-center mt-4">
                                    <h4 class="card-title">{{ $row->product_name }}</h4>
                                    <p class="card-text">{{ Str::limit($row->description ?? 'No description available', 100) }}</p>

                                    <!-- detail -->
                                    <button data-id="{{ $row->id }}"
                                      class="btn btn-primary btn-sm detail">Detail</button>

                                    <!-- ulasan -->
                                    <a href="/review/product/{{ $row->id }}"><button
                                          class="btn btn-primary btn-sm ubah">Review</button></a>

                                    <!-- [admin] actions -->
                                    @can('edit_product',App\Models\Product::class)
                                    <a href="/product/edit_product/{{ $row->id }}"><button
                                          class="btn btn-primary btn-sm ubah">Edit</button></a>
                                    
                                    <!-- Delete button -->
                                    <button class="btn btn-danger btn-sm delete-product" 
                                            data-id="{{ $row->id }}"
                                            data-name="{{ $row->product_name }}"
                                            data-toggle="modal" 
                                            data-target="#deleteConfirmationModal">
                                        Delete
                                    </button>
                                    @endcan
                                    
                                    @can('create_order',App\Models\Order::class)
                                    <a href="/order/make_order/{{ $row->id }}"><button
                                          class="btn btn-success btn-sm ubah">Buy</button></a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ./product card -->
            @endforeach
        </div>
    </div>
</section>
<!-- product -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const productId = button.data('id');
        const productName = button.data('name');
        const modal = $(this);
        
        // Update modal content
        modal.find('.modal-title').text('Delete ' + productName + '?');
        modal.find('.modal-body').html(
            `Are you sure you want to delete <strong>${productName}</strong>?<br>
             This action cannot be undone.`
        );
        
        // Set form action
        modal.find('#deleteForm').attr('action', `/product/delete_product/${productId}`);
    });

    // Optional: AJAX delete handling
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#deleteConfirmationModal').modal('hide');
                if(response.success) {
                    // Show success message
                    alert('Product deleted successfully');
                    // Reload the page or remove the card
                    location.reload();
                }
            },
            error: function(xhr) {
                alert('Error deleting product: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>

@endsection