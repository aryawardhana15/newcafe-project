<?php

namespace App\Http\Controllers;

use App\Models\{Review, Order, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index()
    {
        $title = "Review Produk";
        $reviews = Review::with(['user', 'product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('review.index', compact('title', 'reviews'));
    }

    public function create(Order $order)
    {
        // Check if order is completed and belongs to user
        if ($order->status_id != 4 || $order->user_id != auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda hanya dapat memberikan review untuk pesanan yang sudah selesai.');
        }

        // Check if review already exists
        if (Review::where('order_id', $order->id)->exists()) {
            return redirect()->back()
                ->with('error', 'Anda sudah memberikan review untuk pesanan ini.');
        }

        $title = "Buat Review";
        return view('review.create', compact('title', 'order'));
    }

    public function store(Request $request, Order $order)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
                'image' => 'nullable|image|max:2048'
            ]);

            // Handle image upload
            if ($request->file('image')) {
                $validatedData['image'] = $request->file('image')->store('reviews');
            }

            // Add additional data
            $validatedData['user_id'] = auth()->id();
            $validatedData['product_id'] = $order->product_id;
            $validatedData['order_id'] = $order->id;

            // Create review
            Review::create($validatedData);

            return redirect()->route('review.index')
                ->with('success', 'Review berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan review: ' . $e->getMessage());
        }
    }

    public function edit(Review $review)
    {
        // Check if review belongs to user
        if ($review->user_id != auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
        }

        $title = "Edit Review";
        return view('review.edit', compact('title', 'review'));
    }

    public function update(Request $request, Review $review)
    {
        try {
            // Check if review belongs to user
            if ($review->user_id != auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses untuk mengedit review ini.');
            }

            // Validate request
            $validatedData = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
                'image' => 'nullable|image|max:2048'
            ]);

            // Handle image upload
            if ($request->file('image')) {
                // Delete old image
                if ($review->image) {
                    Storage::delete($review->image);
                }
                $validatedData['image'] = $request->file('image')->store('reviews');
            }

            // Update review
            $review->update($validatedData);

            return redirect()->route('review.index')
                ->with('success', 'Review berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui review: ' . $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        try {
            // Check if review belongs to user
            if ($review->user_id != auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses untuk menghapus review ini.');
            }

            // Delete image if exists
            if ($review->image) {
                Storage::delete($review->image);
            }

            // Delete review
            $review->delete();

            return redirect()->route('review.index')
                ->with('success', 'Review berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus review: ' . $e->getMessage());
        }
    }
}
