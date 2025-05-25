<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'description',
        'income',
        'outcome'
    ];

    protected $casts = [
        'income' => 'decimal:2',
        'outcome' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isIncome()
    {
        return $this->income > 0;
    }

    public function isOutcome()
    {
        return $this->outcome > 0;
    }

    public function getAmount()
    {
        return $this->isIncome() ? $this->income : $this->outcome;
    }

    public function getType()
    {
        return $this->isIncome() ? 'income' : 'outcome';
    }
}
