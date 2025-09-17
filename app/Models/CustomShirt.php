<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomShirt extends Model
{
    // Table name (optional if same as plural of model)
    protected $table = 'custom_shirts';

    // Fields that can be mass assigned
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'design',
        'size',
        'quantity',
        'notes',
        'status',
        'admin_note'
    ];
}
