<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['item_name', 'unit_price', 'quantity', 'order_status', 'payment_status'];
}
