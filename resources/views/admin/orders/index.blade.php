@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pesanan</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="ordersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $order->user->fullname }}</td>
                            <td>{{ $order->product->product_name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp {{ number_format($order->total_price) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status_id == 1 ? 'success' : ($order->status_id == 2 ? 'warning' : 'danger') }}">
                                    {{ $order->status->status_name }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($order->status_id == 2)
                                    <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pesanan ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $order->id }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    @if($order->status_id == 1)
                                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Selesaikan pesanan ini?')">
                                            <i class="fas fa-flag-checkered"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Pesanan #{{ $order->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="refusal_reason">Alasan Penolakan</label>
                                                        <textarea name="refusal_reason" id="refusal_reason" rows="3" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        order: [[0, 'desc']]
    });
});
</script>
@endpush
@endsection 