<?php

namespace App\Http\Controllers;

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

        return view('transaction.index', compact(
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
                'transaction_type' => 'required|in:income,outcome',
                'amount' => 'required|numeric|min:0'
            ], [
                'category_id.required' => 'Kategori harus dipilih',
                'category_id.exists' => 'Kategori tidak valid',
                'description.required' => 'Deskripsi harus diisi',
                'description.max' => 'Deskripsi maksimal 255 karakter',
                'transaction_type.required' => 'Jenis transaksi harus dipilih',
                'transaction_type.in' => 'Jenis transaksi tidak valid',
                'amount.required' => 'Jumlah harus diisi',
                'amount.numeric' => 'Jumlah harus berupa angka',
                'amount.min' => 'Jumlah minimal 0'
            ]);

            $data = [
                'category_id' => $request->category_id,
                'description' => $request->description,
                'income' => $request->transaction_type === 'income' ? $request->amount : 0,
                'outcome' => $request->transaction_type === 'outcome' ? $request->amount : 0
            ];

            Transaction::create($data);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::all();
        return view('transaction.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string|max:255',
                'transaction_type' => 'required|in:income,outcome',
                'amount' => 'required|numeric|min:0'
            ]);

            $data = [
                'category_id' => $request->category_id,
                'description' => $request->description,
                'income' => $request->transaction_type === 'income' ? $request->amount : 0,
                'outcome' => $request->transaction_type === 'outcome' ? $request->amount : 0
            ];

            $transaction->update($data);

            DB::commit();

            return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil diupdate');
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
