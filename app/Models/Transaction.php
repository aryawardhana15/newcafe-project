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
        'type',
        'description',
        'income',
        'outcome'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isIncome()
    {
        return $this->type === 'income';
    }

    public function isOutcome()
    {
        return $this->type === 'outcome';
    }

    public function getAmount()
    {
        return $this->isIncome() ? $this->income : $this->outcome;
    }
}
