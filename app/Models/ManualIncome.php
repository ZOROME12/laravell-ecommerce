<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualIncome extends Model
{
    protected $fillable = ['item_name', 'total_amount', 'quantity'];
}
