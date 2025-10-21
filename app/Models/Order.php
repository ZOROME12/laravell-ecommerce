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
        'order_id', // Included for mass assignment, typically set in creating hook/controller
        'total',
        'subtotal', // Added for payment calculation
        'shipping_cost', // Added for payment calculation
        'payment_method', // Added for payment implementation (e.g., 'gcash_manual')
        'payment_reference_no', // Added for GCash confirmation
        'payment_screenshot_path', // Added for GCash confirmation
        'status',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'size',
        'tracking_stage',
        'origin', // Added if you track single vs cart origin
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

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
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

    public function order()
    {
        // Explicitly defining keys for robustness
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        // Explicitly defining keys for robustness
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
