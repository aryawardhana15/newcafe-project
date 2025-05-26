<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
            
        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);
            
            // Check if already in wishlist
            $exists = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
                
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk sudah ada di wishlist'
                ]);
            }
            
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke wishlist'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke wishlist: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Wishlist $wishlist)
    {
        try {
            if ($wishlist->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }
            
            $wishlist->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari wishlist'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dari wishlist: ' . $e->getMessage()
            ], 500);
        }
    }
} 