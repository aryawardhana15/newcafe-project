<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function process(Order $order)
    {
        $result = $this->midtransService->createTransaction($order);

        if (!$result['success']) {
            return redirect()->back()->with('error', 'Failed to process payment: ' . $result['message']);
        }

        return view('payment.process', [
            'snap_token' => $result['snap_token'],
            'order' => $order,
            'client_key' => config('services.midtrans.client_key')
        ]);
    }

    public function callback(Request $request)
    {
        $result = $this->midtransService->handleCallback($request);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message']
        ]);
    }

    public function finish(Request $request)
    {
        return redirect()->route('order.show', [
            'order' => explode('-', $request->order_id)[1]
        ])->with('success', 'Payment processed successfully');
    }

    public function pending(Request $request)
    {
        return redirect()->route('order.show', [
            'order' => explode('-', $request->order_id)[1]
        ])->with('info', 'Payment is pending. Please complete your payment');
    }

    public function error(Request $request)
    {
        return redirect()->route('order.show', [
            'order' => explode('-', $request->order_id)[1]
        ])->with('error', 'Payment failed. Please try again');
    }
} 