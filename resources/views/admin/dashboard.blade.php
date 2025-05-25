@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pesanan Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingOrders">
                                {{ $pendingOrders }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Keuangan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kategori Produk</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="productCategoryChart"></canvas>
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
                                    <th>Order ID</th>
                                    <th>Pelanggan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status->color }}">
                                            {{ $order->status->name }}
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
                                    <td>{{ $transaction->category->category_name }}</td>
                                    <td>{{ $transaction->description }}</td>
                                    <td>
                                        @if($transaction->income)
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
                                        <span class="badge bg-{{ $transaction->income ? 'success' : 'danger' }}">
                                            {{ $transaction->income ? 'Pemasukan' : 'Pengeluaran' }}
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
    // Finance Chart
    var ctx = document.getElementById('financeChart').getContext('2d');
    var financeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($incomeChart['labels']) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($incomeChart['income']) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 5,
                fill: true
            },
            {
                label: 'Pengeluaran',
                data: {!! json_encode($incomeChart['outcome']) !!},
                backgroundColor: 'rgba(231, 74, 59, 0.05)',
                borderColor: 'rgba(231, 74, 59, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(231, 74, 59, 1)',
                pointBorderColor: 'rgba(231, 74, 59, 1)',
                pointHoverRadius: 5,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        }
                    }
                }
            }
        }
    });

    // Product Category Chart
    var ctx2 = document.getElementById('productCategoryChart').getContext('2d');
    var productCategoryChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryChart['labels']) !!},
            datasets: [{
                data: {!! json_encode($categoryChart['revenue']) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });

    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            
            fetch(`/admin/dashboard/filter/${filter}`)
                .then(response => response.json())
                .then(data => {
                    // Update cards
                    document.getElementById('totalIncome').textContent = 'Rp ' + data.totalIncome;
                    document.getElementById('totalOutcome').textContent = 'Rp ' + data.totalOutcome;
                    document.getElementById('netProfit').textContent = 'Rp ' + data.netProfit;
                    document.getElementById('totalOrders').textContent = data.totalOrders;
                    document.getElementById('totalProducts').textContent = data.totalProducts;
                    document.getElementById('pendingOrders').textContent = data.pendingOrders;

                    // Update charts
                    financeChart.data.labels = data.incomeChart.labels;
                    financeChart.data.datasets[0].data = data.incomeChart.income;
                    financeChart.data.datasets[1].data = data.incomeChart.outcome;
                    financeChart.update();

                    productCategoryChart.data.labels = data.categoryChart.labels;
                    productCategoryChart.data.datasets[0].data = data.categoryChart.revenue;
                    productCategoryChart.update();
                });
        });
    });
});
</script>
@endpush 