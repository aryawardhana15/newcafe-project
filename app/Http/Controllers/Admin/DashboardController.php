<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Card Statistics
        $totalIncome = Transaction::sum('income');
        $totalOutcome = Transaction::sum('outcome');
        $netProfit = $totalIncome - $totalOutcome;
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status_id', 1)->count();

        // Recent Orders
        $recentOrders = Order::with(['status', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Low Stock Products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with(['category'])
            ->latest()
            ->take(5)
            ->get();

        // Income Chart Data (Last 7 days)
        $incomeChart = $this->getIncomeChartData();

        // Category Chart Data
        $categoryChart = $this->getCategoryChartData();

        return view('admin.dashboard', compact(
            'totalIncome',
            'totalOutcome',
            'netProfit',
            'totalOrders',
            'totalProducts',
            'pendingOrders',
            'recentOrders',
            'lowStockProducts',
            'recentTransactions',
            'incomeChart',
            'categoryChart'
        ));
    }

    public function filter($period)
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'today':
                $start = $now->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'week':
                $start = $now->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start = $now->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start = $now->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        // Get filtered data
        $totalIncome = Transaction::whereBetween('created_at', [$start, $end])->sum('income');
        $totalOutcome = Transaction::whereBetween('created_at', [$start, $end])->sum('outcome');
        $netProfit = $totalIncome - $totalOutcome;
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status_id', 1)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // Get chart data for the period
        $incomeChart = $this->getIncomeChartData($start, $end);
        $categoryChart = $this->getCategoryChartData($start, $end);

        return response()->json([
            'totalIncome' => number_format($totalIncome, 0, ',', '.'),
            'totalOutcome' => number_format($totalOutcome, 0, ',', '.'),
            'netProfit' => number_format($netProfit, 0, ',', '.'),
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'pendingOrders' => $pendingOrders,
            'incomeChart' => $incomeChart,
            'categoryChart' => $categoryChart
        ]);
    }

    private function getIncomeChartData($start = null, $end = null)
    {
        if (!$start) {
            $start = Carbon::now()->subDays(6)->startOfDay();
        }
        if (!$end) {
            $end = Carbon::now()->endOfDay();
        }

        $transactions = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(income) as total_income'),
            DB::raw('SUM(outcome) as total_outcome')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $transactions->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'income' => $transactions->pluck('total_income'),
            'outcome' => $transactions->pluck('total_outcome')
        ];
    }

    private function getCategoryChartData($start = null, $end = null)
    {
        $query = Order::with(['product.category'])
            ->select(
                'products.category_id',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_revenue')
            )
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.status_id', 4) // Hanya pesanan yang selesai
            ->groupBy('products.category_id');

        if ($start && $end) {
            $query->whereBetween('orders.created_at', [$start, $end]);
        }

        $categoryData = $query->get();

        return [
            'labels' => $categoryData->map(function($item) {
                return $item->product->category->category_name ?? 'Tidak Ada Kategori';
            })->toArray(),
            'data' => $categoryData->pluck('total_revenue')->toArray()
        ];
    }
} 