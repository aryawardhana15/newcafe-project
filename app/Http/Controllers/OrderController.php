<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\{Auth, Storage, Validator, DB};
use App\Models\{Order, Status, Product, Role, Transaction, User, Bank};
use App\Events\OrderStatusUpdated;

class OrderController extends Controller
{
    public function makeOrderGet(Product $product)
    {
        // Validasi stok
        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok produk tidak tersedia');
        }

        $title = "Buat Pesanan";
        $banks = Bank::all();
        
        // Get active orders for current user
        $activeOrders = Order::with(['product', 'status', 'payment', 'bank'])
            ->where('user_id', auth()->id())
            ->where('is_done', 0)
            ->whereNotIn('status_id', [3, 4, 5]) // Exclude rejected, done, and cancelled orders
            ->latest()
            ->take(5)
            ->get();
        
        return view("/order/make_order", compact("title", "product", "banks", "activeOrders"));
    }


    public function makeOrderPost(Request $request, Product $product)
    {
        \DB::beginTransaction();
        
        try {
            // Validate request
            $rules = [
                'address' => 'required|string|max:255',
                'payment_method' => 'required|in:1,2',
                'quantity' => 'required|integer|min:1|max:' . $product->stock,
                'total_price' => 'required|numeric|min:0',
                'shipping_address' => 'required|string',
                'coupon_used' => 'required|integer|min:0'
            ];

            // Validate bank_id only if payment method is bank transfer
            if ($request->payment_method == 1) {
                $rules['bank_id'] = 'required|exists:banks,id';
            }

            $validatedData = $request->validate($rules);

            // Check stock availability
            if ($product->stock < $validatedData['quantity']) {
                throw new \Exception('Stok produk tidak mencukupi');
            }

            // Check coupon availability if used
            if ($validatedData['coupon_used'] > 0) {
                $user = auth()->user();
                if ($user->coupon < $validatedData['coupon_used']) {
                    throw new \Exception('Kupon tidak mencukupi');
                }
            }

            // Prepare order data
            $orderData = [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'quantity' => $validatedData['quantity'],
                'address' => $validatedData['address'],
                'shipping_address' => $validatedData['shipping_address'],
                'total_price' => $validatedData['total_price'],
                'payment_id' => $validatedData['payment_method'],
                'bank_id' => $validatedData['payment_method'] == 1 ? $validatedData['bank_id'] : null,
                'note_id' => $validatedData['payment_method'] == 1 ? 2 : 1, // 2 for bank transfer, 1 for COD
                'status_id' => 2, // Pending status
                'transaction_doc' => null,
                'is_done' => 0,
                'coupon_used' => $validatedData['coupon_used']
            ];

            // Create order
            $order = Order::create($orderData);

            if (!$order) {
                throw new \Exception('Gagal membuat pesanan');
            }

            // Update product stock
            $product->decrement('stock', $validatedData['quantity']);

            // Update user's coupon if used
            if ($validatedData['coupon_used'] > 0) {
                auth()->user()->decrement('coupon', $validatedData['coupon_used']);
            }

            \DB::commit();

            return redirect('/order/order_data')
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran sesuai metode yang dipilih.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            \Log::error('Order validation failed: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
            
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Order creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }


    public function orderData()
    {
        try {
            $title = 'Data Pesanan';
            
            // Get active orders
            $query = Order::with(['bank', 'note', 'payment', 'user', 'status', 'product'])
                ->where('is_done', 0)
                ->whereNotIn('status_id', [3, 4, 5]); // Exclude rejected, done, and cancelled orders

            // Filter based on role
            if (auth()->user()->role_id != Role::ADMIN_ID) {
                $query->where('user_id', auth()->id());
            }

            // Get orders with all needed relations
            $orders = $query->latest()->get();

            // Get active orders for sidebar
            $activeOrders = Order::with(['product', 'status', 'payment', 'bank'])
                ->where('user_id', auth()->id())
                ->where('is_done', 0)
                ->whereNotIn('status_id', [3, 4, 5]) // Exclude rejected, done, and cancelled orders
                ->latest()
                ->take(5)
                ->get();

            // Load status for filter
            $status = Status::orderBy('id')->get();

            return view('/order/order_data', compact('title', 'orders', 'status', 'activeOrders'));
        } catch (\Exception $e) {
            \Log::error('Error fetching order data: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data pesanan: ' . $e->getMessage());
        }
    }


    public function orderDataFilter($status_id)
    {
        try {
            $title = "Order Data";
            $query = Order::with(['bank', 'note', 'payment', 'user', 'status', 'product'])
                ->where('status_id', $status_id)
                ->where('is_done', 0);

            // Filter berdasarkan role
            if (auth()->user()->role_id != Role::ADMIN_ID) {
                $query->where('user_id', auth()->id());
            }

            $orders = $query->latest()->get();
            $status = Status::orderBy('id')->get();

            return view("/order/order_data", compact("title", "orders", "status"));
        } catch (\Exception $e) {
            \Log::error('Error filtering order data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memfilter data pesanan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getOrderDetail(Order $order)
    {
        try {
            // Authorize access
            if (auth()->user()->role_id != Role::ADMIN_ID && auth()->id() != $order->user_id) {
                throw new \Exception('Unauthorized access');
            }

            $order->load(['product', 'user', 'note', 'status', 'bank', 'payment']);
            
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail pesanan: ' . $e->getMessage()
            ], 403);
        }
    }


    public function getOrderStatus(Order $order)
    {
        try {
            // Authorize access
            if (auth()->user()->role_id != Role::ADMIN_ID) {
                throw new \Exception('Unauthorized access');
            }

            $order->load(['status']);
            $allStatus = Status::orderBy('id')->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'order' => $order,
                    'allStatus' => $allStatus
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function cancelOrder(Order $order)
    {
        if ($order->status_id == 5) {
            $message = "Your order is already canceled!";

            myFlasherBuilder(message: $message, failed: true);
            return redirect("/order/order_data");
        }
        $updated_data = [
            "status_id" => 5,
            "note_id" => 6,
            "refusal_reason" => null,
        ];

        $order->fill($updated_data);

        if ($order->isDirty()) {
            $order->save();

            $this->couponBack($order);

            $message = "Your order has been canceled!";

            myFlasherBuilder(message: $message, success: true);
            return redirect("/order/order_data");
        }
    }


    private function couponBack(Order $order)
    {
        // return the user's coupon if using a coupon
        $user = Auth::user();

        $new_coupon = (int)$user->coupon + (int)$order->coupon_used;

        $user->coupon = $new_coupon;

        if ($user->isDirty()) {
            $user->save();
        }
    }


    public function rejectOrder(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();

            // Validasi alasan penolakan
            $request->validate([
                'refusal_reason' => 'required|string|max:255'
            ], [
                'refusal_reason.required' => 'Alasan penolakan harus diisi'
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

            // Kirim notifikasi ke user
            event(new OrderStatusUpdated($order, 'Pesanan Anda ditolak: ' . $request->refusal_reason));

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function approveOrder(Order $order)
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

            // Kurangi stok produk
            $product = $order->product;
            if ($product->stock < $order->quantity) {
                throw new \Exception('Stok produk tidak mencukupi');
            }
            $product->decrement('stock', $order->quantity);

            // Kirim notifikasi ke user
            event(new OrderStatusUpdated($order, 'Pesanan Anda telah disetujui'));

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function endOrder(Order $order)
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
            Transaction::create([
                'category_id' => 1, // Sales
                'description' => "Penjualan {$order->quantity} {$order->product->product_name}",
                'income' => $order->total_price,
                'outcome' => null
            ]);

            // Kirim notifikasi ke user
            event(new OrderStatusUpdated($order, "Pesanan Anda telah selesai. Anda mendapatkan {$pointEarned} poin!"));

            DB::commit();

            return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function orderHistory()
    {
        try {
            $title = 'Riwayat Pesanan';
            
            // Get completed, rejected, or cancelled orders
            $query = Order::with(['bank', 'note', 'payment', 'user', 'status', 'product', 'review'])
                ->where(function($q) {
                    $q->where('is_done', 1)
                      ->orWhereIn('status_id', [3, 4, 5]); // Include rejected, done, and cancelled orders
                });

            // Filter based on role
            if (auth()->user()->role_id != Role::ADMIN_ID) {
                $query->where('user_id', auth()->id());
            }

            // Get orders with all needed relations
            $orders = $query->latest()->get();

            // Load status for filter
            $status = Status::orderBy('id')->get();

            return view('/order/order_history', compact('title', 'orders', 'status'));
        } catch (\Exception $e) {
            \Log::error('Error fetching order history: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat riwayat pesanan: ' . $e->getMessage());
        }
    }


    public function getProofOrder(Order $order)
    {
        $order->load("status");
        return  $order;
    }


    public function uploadProof(Request $request, Order $order)
    {
        try {
            // Authorize access
            if (auth()->id() != $order->user_id) {
                throw new \Exception('Unauthorized access');
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'image_upload_proof' => 'required|image|file|max:2048'
            ], [
                'image_upload_proof.required' => 'File bukti pembayaran harus diupload',
                'image_upload_proof.image' => 'File harus berupa gambar',
                'image_upload_proof.max' => 'Ukuran file maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Delete old image if exists
                if ($order->transaction_doc && $order->transaction_doc != env('IMAGE_PROOF')) {
                    Storage::delete($order->transaction_doc);
                }

                // Store new image
                $newImage = $request->file('image_upload_proof')->store('proof');

                // Update order
                $order->update([
                    'transaction_doc' => $newImage,
                    'note_id' => 3 // Payment proof uploaded
                ]);

                // Trigger notification event
                event(new OrderStatusUpdated($order, "Bukti pembayaran untuk Order #{$order->id} telah diupload"));

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diupload'
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Upload proof failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }


    public function editOrderGet(Order $order)
    {
        if ($order->status_id == 5) {
            $message = "Action failed, order is already canceled by the user";
            myFlasherBuilder(message: $message, failed: true);

            return redirect("/order/order_data/");
        }

        $title = "Edit Order";
        $order->load("product", "user", "note", "status", "bank", "payment");

        return view("/order/edit_order", compact("title", "order"));
    }

    public function editOrderPost(Request $request, Order $order)
    {
        $rules = [
            'address' => 'required|max:255',
            'quantity' => 'required|numeric|gt:0|lte:' . $order->product->stock,
            'total_price' => 'required|gt:0',
            'shipping_address' => 'required',
            'coupon_used' => 'required|gte:0'
        ];


        $message = [
            'quantity.lte' => 'sorry the current available stock is ' . $order->product->stock,
        ];

        if ($request->file("image_proof_edit")) {
            $rules["image_proof_edit"] = "image|file|max:2048";
        }

        $validatedData = $request->validate($rules, $message);

        if ($request->file("image_proof_edit")) {
            if ($order->transaction_doc != env("IMAGE_PROOF")) {
                Storage::delete($order->transaction_doc);
            }

            $validatedData["transaction_doc"] = $request->file("image_proof_edit")->store("proof");
        }

        $order->fill($validatedData);

        if ($order->isDirty()) {

            $order->save();

            $message = "Order has beed updated!";
            myFlasherBuilder(message: $message, success: true);

            return redirect("/order/order_data");
        } else {
            $message = "Action failed, no changes detected";
            myFlasherBuilder(message: $message, failed: true);

            return redirect("/order/edit_order/" . $order->id);
        }
    }


    public function deleteProof(Order $order)
    {
        if ($order->transaction_doc != env("IMAGE_PROOF")) {
            Storage::delete($order->transaction_doc);
        }

        $order->transaction_doc = env("IMAGE_PROOF");

        $order->save();

        $message = "Transfer proof removed successfully!";
        myFlasherBuilder(message: $message, success: true);

        return redirect("/order/edit_order/" . $order->id);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'address' => 'required|string',
            'shipping_address' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_method' => 'required|in:1,2',
            'bank_id' => 'required_if:payment_method,1|exists:banks,id',
            'coupon_used' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Validasi stok
            $product = Product::findOrFail($validatedData['product_id']);
            if ($product->stock < $validatedData['quantity']) {
                throw new \Exception('Stok produk tidak mencukupi');
            }

            // Validasi kupon
            if ($validatedData['coupon_used'] > 0) {
                $user = auth()->user();
                if ($user->coupon < $validatedData['coupon_used']) {
                    throw new \Exception('Kupon tidak mencukupi');
                }
            }

            // Buat order
            $order = Order::create([
                'user_id' => auth()->id(),
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'address' => $validatedData['address'],
                'shipping_address' => $validatedData['shipping_address'],
                'total_price' => $validatedData['total_price'],
                'payment_id' => $validatedData['payment_method'],
                'bank_id' => $validatedData['payment_method'] == 1 ? $validatedData['bank_id'] : null,
                'status_id' => 2, // Pending
                'note_id' => $validatedData['payment_method'] == 1 ? 2 : 1, // 2 for bank transfer, 1 for COD
                'transaction_doc' => null,
                'is_done' => 0,
                'coupon_used' => $validatedData['coupon_used']
            ]);

            // Update stok produk
            $product->decrement('stock', $validatedData['quantity']);

            // Update kupon user jika digunakan
            if ($validatedData['coupon_used'] > 0) {
                auth()->user()->decrement('coupon', $validatedData['coupon_used']);
            }

            DB::commit();

            return redirect()->route('order.data')
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran sesuai metode yang dipilih.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Authorize access
        if (auth()->user()->role_id != Role::ADMIN_ID) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $request->validate([
            'status_id' => 'required|exists:status,id'
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $order->status->order_status;
            $order->update(['status_id' => $request->status_id]);
            $newStatus = $order->fresh()->status->order_status;

            // Send notification
            event(new OrderStatusUpdated($order, "Status pesanan #$order->id berubah dari $oldStatus menjadi $newStatus"));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pesanan berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
