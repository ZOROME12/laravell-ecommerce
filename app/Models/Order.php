<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'size',
        'tracking_stage', 
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->order_id) {
                $order->order_id = 'EASE-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fix: specify foreign and local keys
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'size',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
