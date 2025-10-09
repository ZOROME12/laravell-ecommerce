<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Include delivery fields and payment method
    protected $fillable = [
        'user_id',
        'total',
        'status',
        'delivery_name',
        'delivery_phone',
        'size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price' , 'size'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
