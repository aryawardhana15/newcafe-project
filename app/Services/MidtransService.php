<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function createTransaction(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->id . '-' . time(),
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ],
            'item_details' => [
                [
                    'id' => $order->product->id,
                    'price' => $order->product->price,
                    'quantity' => $order->quantity,
                    'name' => $order->product->product_name,
                ]
            ],
            'enabled_payments' => [
                'credit_card', 'bca_va', 'bni_va', 'bri_va',
                'echannel', 'permata_va', 'gopay', 'shopeepay'
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'error' => route('payment.error'),
                'pending' => route('payment.pending'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return [
                'success' => true,
                'snap_token' => $snapToken,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function handleCallback($request)
    {
        $orderId = explode('-', $request->order_id)[1];
        $order = Order::find($orderId);

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Order not found',
            ];
        }

        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;
        $fraudStatus = $request->fraud_status;

        $status = match($transactionStatus) {
            'capture' => $fraudStatus == 'accept' ? 'success' : 'failed',
            'settlement' => 'success',
            'pending' => 'pending',
            'deny', 'expire', 'cancel' => 'failed',
            default => 'failed'
        };

        $order->update([
            'payment_status' => $status,
            'payment_type' => $type,
            'status_id' => $status == 'success' ? 1 : ($status == 'pending' ? 2 : 3),
        ]);

        return [
            'success' => true,
            'message' => 'Payment status updated',
        ];
    }
} 