@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <!-- Categories Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="categoriesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->image)
                                <img src="{{ Storage::url('categories/'.$category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 50px;">
                                @else
                                <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $category->products_count }} Produk
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.categories.edit', $category) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" 
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
    $('#categoriesTable').DataTable({
        order: [[0, 'desc']]
    });

    // Konfirmasi delete
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua produk dalam kategori ini juga akan dihapus.')) {
            this.submit();
        }
    });
});
</script>
@endpush 