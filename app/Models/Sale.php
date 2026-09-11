<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
     protected $fillable = [
        'country',
        'item_type',
        'sales_channel',
        'order_id',
        'unit_price',
        'total_profit',
    ];
}
