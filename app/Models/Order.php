<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    // Explicitly define fillable fields
    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'address',
        'shipping_address',
        'total_price',
        'payment_id',
        'bank_id',
        'note_id',
        'status_id',
        'transaction_doc',
        'is_done',
        'refusal_reason',
        'coupon_used'
    ];

    // Define relationships
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
