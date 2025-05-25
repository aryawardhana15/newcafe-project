<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'user', 'status', 'payment', 'bank'])
            ->where('is_done', 0)
            ->whereNotIn('status_id', [3, 4, 5]) // Exclude rejected, done, and cancelled orders
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function history()
    {
        $orders = Order::with(['product', 'user', 'status', 'payment', 'bank'])
            ->where(function($query) {
                $query->where('is_done', 1)
                    ->orWhereIn('status_id', [3, 4, 5]); // Include rejected, done, and cancelled orders
            })
            ->latest()
            ->get();

        return view('admin.orders.history', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['product', 'user', 'status', 'payment', 'bank']);
        return view('admin.orders.show', compact('order'));
    }

    public function approve(Order $order)
    {
        try {
            DB::beginTransaction();

            // Validasi status pesanan
            if ($order->status_id != 2) {
                throw new \Exception('Pesanan tidak dapat disetujui karena status tidak sesuai');
            }

            // Validasi bukti pembayaran untuk metode bank transfer
            if ($order->payment_id == 1 && (!$order->transaction_doc || $order->transaction_doc == env('IMAGE_PROOF'))) {
                throw new \Exception('Bukti pembayaran belum diupload');
            }

            // Update status pesanan
            $order->update([
                'status_id' => 1, // Status Approved
                'note_id' => $order->payment_id == 1 ? 4 : 1, // 4 untuk bank transfer, 1 untuk COD
                'refusal_reason' => null
            ]);

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil disetujui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // Validasi alasan penolakan
            $request->validate([
                'refusal_reason' => 'required|string|max:255'
            ]);

            // Validasi status pesanan
            if (!in_array($order->status_id, [1, 2])) {
                throw new \Exception('Pesanan tidak dapat ditolak karena status tidak sesuai');
            }

            // Update status pesanan
            $order->update([
                'status_id' => 3, // Status Rejected
                'refusal_reason' => $request->refusal_reason
            ]);

            // Kembalikan stok jika sebelumnya sudah diapprove
            if ($order->status_id == 1) {
                $order->product->increment('stock', $order->quantity);
            }

            // Kembalikan kupon jika digunakan
            if ($order->coupon_used > 0) {
                $order->user->increment('coupon', $order->coupon_used);
            }

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil ditolak');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function complete(Order $order)
    {
        try {
            DB::beginTransaction();

            // Validasi status pesanan
            if ($order->status_id != 1) {
                throw new \Exception('Pesanan belum disetujui atau sudah selesai');
            }

            // Update status pesanan
            $order->update([
                'status_id' => 4, // Status Done
                'note_id' => 5,
                'is_done' => 1
            ]);

            // Tambah point ke user
            $pointRules = [
                1 => 3, // Arabica
                2 => 4, // Robusta
                3 => 5  // Liberica
            ];

            $pointEarned = ($pointRules[$order->product_id] ?? 1) * $order->quantity;
            $order->user->increment('point', $pointEarned);

            // Catat transaksi
            DB::table('transactions')->insert([
                'category_id' => 1, // Sales
                'type' => 'income',
                'description' => "Penjualan {$order->quantity} {$order->product->product_name}",
                'amount' => $order->total_price,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan berhasil diselesaikan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
} 