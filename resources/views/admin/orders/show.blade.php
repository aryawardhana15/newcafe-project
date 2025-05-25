@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
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

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Status Pesanan</h6>
                            <span class="badge bg-{{ $order->status_id == 1 ? 'success' : ($order->status_id == 2 ? 'warning' : 'danger') }}">
                                {{ $order->status->status_name }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6>Tanggal Pesanan</h6>
                            <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Metode Pembayaran</h6>
                            <p>
                                {{ $order->payment->payment_method }}
                                @if($order->payment_id == 1 && $order->bank)
                                <br>
                                <small class="text-muted">{{ $order->bank->bank_name }} - {{ $order->bank->account_number }}</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Pembayaran</h6>
                            <p>Rp {{ number_format($order->total_price) }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h6>Bukti Pembayaran</h6>
                            @if($order->transaction_doc && $order->transaction_doc != env('IMAGE_PROOF'))
                            <img src="{{ asset('storage/' . $order->transaction_doc) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="img-fluid mb-2" 
                                 style="max-height: 200px;">
                            @else
                            <p class="text-muted">Belum ada bukti pembayaran</p>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6>Alamat Pengiriman</h6>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <img src="{{ asset('storage/' . $order->product->image) }}" 
                             alt="{{ $order->product->product_name }}"
                             class="img-fluid rounded me-3"
                             style="width: 100px; height: 100px; object-fit: cover;">
                        <div>
                            <h5 class="mb-1">{{ $order->product->product_name }}</h5>
                            <p class="mb-1">{{ $order->quantity }} x Rp {{ number_format($order->product->price) }}</p>
                            <p class="mb-0 text-muted">Total: Rp {{ number_format($order->total_price) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/' . $order->user->image) }}" 
                             alt="{{ $order->user->fullname }}"
                             class="rounded-circle me-2"
                             style="width: 48px; height: 48px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">{{ $order->user->fullname }}</h6>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6>Nomor Telepon</h6>
                        <p>{{ $order->user->phone }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Alamat</h6>
                        <p>{{ $order->user->address }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if($order->status_id == 2)
                    <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui pesanan ini?')">
                            <i class="fas fa-check me-1"></i> Setujui Pesanan
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-1"></i> Tolak Pesanan
                    </button>
                    @endif

                    @if($order->status_id == 1)
                    <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Selesaikan pesanan ini?')">
                            <i class="fas fa-flag-checkered me-1"></i> Selesaikan Pesanan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
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
@endsection 