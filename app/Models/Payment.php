<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    const BANK_TRANSFER = 1;
    const CASH_ON_DELIVERY = 2;

    public function isBankTransfer()
    {
        return $this->id === self::BANK_TRANSFER;
    }

    public function isCashOnDelivery()
    {
        return $this->id === self::CASH_ON_DELIVERY;
    }
}
