<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role_id', 2)->count();
        
        // Pendapatan & Pengeluaran
        $totalIncome = Transaction::where('type', 'income')->sum('income');
        $totalOutcome = Transaction::where('type', 'outcome')->sum('outcome');
        $netIncome = $totalIncome - $totalOutcome;

        // Pesanan Terbaru
        $recentOrders = Order::with(['user', 'product', 'status'])
            ->latest()
            ->take(5)
            ->get();

        // Grafik Penjualan 7 Hari Terakhir
        $salesData = Order::where('is_done', 1)
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produk Terlaris
        $topProducts = Order::where('is_done', 1)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalIncome',
            'totalOutcome',
            'netIncome',
            'recentOrders',
            'salesData',
            'topProducts'
        ));
    }
} 