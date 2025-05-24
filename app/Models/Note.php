<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'style'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
