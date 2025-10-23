<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReportLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sales_report_log';

    /**
     * Indicates if the model should be timestamped.
     * We only need created_at, which is handled automatically
     * if the column exists and isn't explicitly excluded.
     * Set updated_at to false if you don't have an updated_at column in your migration.
     *
     * @var bool
     */
    public $timestamps = true; // Assumes you have created_at, handles it automatically

    /**
     * We don't need updated_at for this log table.
     */
    const UPDATED_AT = null;


    /**
     * The attributes that are mass assignable.
     * Make sure these match the columns in your migration.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'order_date',
        'customer_name',
        'product_name',
        'product_category',
        'quantity',
        'price_per_item',
        'line_total',
        'source',
        // 'created_at' is handled automatically
    ];

    /**
     * The attributes that should be cast.
     * Cast dates to Carbon instances for easier handling.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_date' => 'datetime',
        'price_per_item' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];
}
