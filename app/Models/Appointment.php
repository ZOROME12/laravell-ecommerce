<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'schedule_date',
        'notes',
        'status',
        'token',
        'qr_code_path',
        'pdf_path'
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];
}
