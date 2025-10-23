<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'item_name',
        'unit_price',
        'quantity',
        'order_status',
        'payment_status',
        'category_id', 
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
