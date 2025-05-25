<?php

namespace App\Models;

use App\Models\{Order, Review};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_available'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'float',
        'stock' => 'integer'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }
}
