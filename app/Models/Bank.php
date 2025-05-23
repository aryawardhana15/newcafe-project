<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'logo'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
