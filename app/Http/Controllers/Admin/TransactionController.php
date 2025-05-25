<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['category'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalIncome = Transaction::sum('income');
        $totalOutcome = Transaction::sum('outcome');
        $totalTransactions = Transaction::count();
        $categories = Category::all();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalIncome',
            'totalOutcome',
            'totalTransactions',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string|max:255',
                'type' => 'required|in:income,outcome',
                'amount' => 'required|numeric|min:0'
            ], [
                'category_id.required' => 'Kategori harus dipilih',
                'category_id.exists' => 'Kategori tidak valid',
                'description.required' => 'Deskripsi harus diisi',
                'description.max' => 'Deskripsi maksimal 255 karakter',
                'type.required' => 'Tipe transaksi harus dipilih',
                'type.in' => 'Tipe transaksi tidak valid',
                'amount.required' => 'Jumlah harus diisi',
                'amount.numeric' => 'Jumlah harus berupa angka',
                'amount.min' => 'Jumlah minimal 0'
            ]);

            Transaction::create([
                'category_id' => $request->category_id,
                'description' => $request->description,
                'income' => $request->type === 'income' ? $request->amount : 0,
                'outcome' => $request->type === 'outcome' ? $request->amount : 0
            ]);

            DB::commit();
            return redirect()->route('admin.transactions.index')
                ->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::all();
        return view('admin.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string|max:255',
                'type' => 'required|in:income,outcome',
                'amount' => 'required|numeric|min:0'
            ]);

            $transaction->update([
                'category_id' => $request->category_id,
                'description' => $request->description,
                'income' => $request->type === 'income' ? $request->amount : 0,
                'outcome' => $request->type === 'outcome' ? $request->amount : 0
            ]);

            DB::commit();

            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 