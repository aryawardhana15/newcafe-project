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

        $totalIncome = Transaction::where('type', 'income')->sum('income');
        $totalOutcome = Transaction::where('type', 'outcome')->sum('outcome');
        $totalTransactions = Transaction::count();
        $categories = Category::where('type', 'transaction')->get();

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
                'income' => 'nullable|numeric|min:0',
                'outcome' => 'nullable|numeric|min:0'
            ], [
                'category_id.required' => 'Kategori harus dipilih',
                'category_id.exists' => 'Kategori tidak valid',
                'description.required' => 'Deskripsi harus diisi',
                'description.max' => 'Deskripsi maksimal 255 karakter',
                'income.numeric' => 'Pemasukan harus berupa angka',
                'income.min' => 'Pemasukan minimal 0',
                'outcome.numeric' => 'Pengeluaran harus berupa angka',
                'outcome.min' => 'Pengeluaran minimal 0'
            ]);

            // Validasi income dan outcome
            if (!$request->income && !$request->outcome) {
                throw new \Exception('Pemasukan atau pengeluaran harus diisi');
            }

            if ($request->income && $request->outcome) {
                throw new \Exception('Tidak bisa mengisi pemasukan dan pengeluaran sekaligus');
            }

            Transaction::create([
                'category_id' => $request->category_id,
                'type' => $request->income ? 'income' : 'outcome',
                'description' => $request->description,
                'income' => $request->income,
                'outcome' => $request->outcome
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        $categories = Category::where('type', 'transaction')->get();
        return view('admin.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string|max:255',
                'income' => 'nullable|numeric|min:0',
                'outcome' => 'nullable|numeric|min:0'
            ]);

            // Validasi income dan outcome
            if (!$request->income && !$request->outcome) {
                throw new \Exception('Pemasukan atau pengeluaran harus diisi');
            }

            if ($request->income && $request->outcome) {
                throw new \Exception('Tidak bisa mengisi pemasukan dan pengeluaran sekaligus');
            }

            $transaction->update([
                'category_id' => $request->category_id,
                'type' => $request->income ? 'income' : 'outcome',
                'description' => $request->description,
                'income' => $request->income,
                'outcome' => $request->outcome
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