@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ Storage::url('products/'.$product->image) }}" 
                                     alt="{{ $product->product_name }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 50px;">
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input status-switch" 
                                           id="status{{ $product->id }}"
                                           data-id="{{ $product->id }}"
                                           {{ $product->is_available ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status{{ $product->id }}">
                                        {{ $product->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#productsTable').DataTable({
        order: [[0, 'desc']]
    });

    // Konfirmasi delete
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
            this.submit();
        }
    });

    // Update status
    $('.status-switch').on('change', function() {
        const productId = $(this).data('id');
        const label = $(this).next('label');
        
        $.ajax({
            url: `/admin/products/${productId}/status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    label.text(response.is_available ? 'Tersedia' : 'Tidak Tersedia');
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat mengubah status');
                // Revert switch state
                $(this).prop('checked', !$(this).prop('checked'));
            }
        });
    });
});
</script>
@endpush 