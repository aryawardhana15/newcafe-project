@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                <i class="fas fa-calendar"></i> Filter
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item filter-btn" data-filter="today" href="#">Hari Ini</a></li>
                <li><a class="dropdown-item filter-btn" data-filter="week" href="#">Minggu Ini</a></li>
                <li><a class="dropdown-item filter-btn" data-filter="month" href="#">Bulan Ini</a></li>
                <li><a class="dropdown-item filter-btn" data-filter="year" href="#">Tahun Ini</a></li>
            </ul>
        </div>
    </div>

    <!-- Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalIncome">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Pengeluaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalOutcome">
                                Rp {{ number_format($totalOutcome, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Laba Bersih
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="netProfit">
                                Rp {{ number_format($netProfit, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalOrders">
                                {{ $totalOrders }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xl-6 col-lg-6">
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
                                    <th>Produk</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->product->product_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status->id == 1 ? 'success' : 'warning' }}">
                                            {{ $order->status->status_name }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        @if($transaction->category)
                                            <span class="badge bg-{{ $transaction->income > 0 ? 'success' : 'danger' }}">
                                                {{ $transaction->category->category_name }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Ada Kategori</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        @if($transaction->income > 0)
                                            <span class="text-success">
                                                + Rp {{ number_format($transaction->income, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                - Rp {{ number_format($transaction->outcome, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->income > 0 ? 'success' : 'danger' }}">
                                            {{ $transaction->income > 0 ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.chart-area {
    height: 300px;
}
.chart-pie {
    height: 300px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Income Chart
    const incomeCtx = document.getElementById('incomeChart').getContext('2d');
    const incomeChart = new Chart(incomeCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($incomeChart['labels']) !!},
            datasets: [{
                label: 'Pemasukan',
                data: {!! json_encode($incomeChart['income']) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true
            }, {
                label: 'Pengeluaran',
                data: {!! json_encode($incomeChart['outcome']) !!},
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryChart['labels']) !!},
            datasets: [{
                data: {!! json_encode($categoryChart['data']) !!},
                backgroundColor: [
                    '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', 
                    '#e74a3b', '#858796', '#5a5c69', '#2c9faf',
                    '#f8f9fc'
                ],
                hoverBackgroundColor: [
                    '#2e59d9', '#17a673', '#2c9faf', '#f4b619',
                    '#be2617', '#60616f', '#373840', '#1a7081',
                    '#e5e9f2'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            return `${context.label}: Rp ${formattedValue}`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const period = this.dataset.filter;
            
            fetch(`/admin/dashboard/filter/${period}`)
                .then(response => response.json())
                .then(data => {
                    // Update statistics
                    document.getElementById('totalIncome').textContent = 'Rp ' + data.totalIncome;
                    document.getElementById('totalOutcome').textContent = 'Rp ' + data.totalOutcome;
                    document.getElementById('netProfit').textContent = 'Rp ' + data.netProfit;
                    document.getElementById('totalOrders').textContent = data.totalOrders;
                    
                    // Update charts
                    incomeChart.data.labels = data.incomeChart.labels;
                    incomeChart.data.datasets[0].data = data.incomeChart.income;
                    incomeChart.data.datasets[1].data = data.incomeChart.outcome;
                    incomeChart.update();

                    categoryChart.data.labels = data.categoryChart.labels;
                    categoryChart.data.datasets[0].data = data.categoryChart.data;
                    categoryChart.update();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memfilter data');
                });
        });
    });
});
</script>
@endpush 