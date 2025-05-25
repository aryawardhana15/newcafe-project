<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $message;

    public function __construct(Order $order, $message)
    {
        $this->order = $order;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('orders.' . $this->order->user_id);
    }

    public function broadcastAs()
    {
        return 'order.status.updated';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status->order_status,
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString()
        ];
    }
} 