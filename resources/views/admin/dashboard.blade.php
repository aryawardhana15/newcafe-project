@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
        <!-- Total Orders Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coffee fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pelanggan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Income Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendapatan Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($netIncome) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    @foreach($topProducts as $product)
                    <div class="mb-3">
                        <div class="font-weight-bold text-gray-800">{{ $product->product->product_name }}</div>
                        <div class="text-gray-600 small">Terjual: {{ $product->total_sold }} unit</div>
                        <div class="progress" style="height: 0.5rem;">
                            <div class="progress-bar" role="progressbar" style="width: {{ ($product->total_sold / $topProducts->max('total_sold')) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->fullname }}</td>
                            <td>{{ $order->product->product_name }}</td>
                            <td>Rp {{ number_format($order->total_price) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status->style }}">
                                    {{ $order->status->status_name }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk grafik
    const salesData = @json($salesData);
    
    // Persiapkan data untuk Chart.js
    const labels = salesData.map(item => item.date);
    const data = salesData.map(item => item.total);
    
    // Buat grafik
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Penjualan',
                data: data,
                borderColor: '#4e73df',
                tension: 0.3,
                fill: true,
                backgroundColor: 'rgba(78, 115, 223, 0.05)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush 