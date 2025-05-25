@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Point</th>
                            <th>Kupon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role_id == 1 ? 'primary' : 'secondary' }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.update-point', $user) }}" method="POST" class="d-inline point-form">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="point" value="{{ $user->point }}" class="form-control" min="0">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.update-coupon', $user) }}" method="POST" class="d-inline coupon-form">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="coupon" value="{{ $user->coupon }}" class="form-control" min="0">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
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
    $('#usersTable').DataTable({
        order: [[0, 'desc']]
    });

    // Konfirmasi delete
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
            this.submit();
        }
    });

    // Auto-submit point form on change
    $('.point-form input').on('change', function() {
        $(this).closest('form').submit();
    });

    // Auto-submit coupon form on change
    $('.coupon-form input').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush 