<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'orders';
    protected $fillable = [
        'session_id',
        'restaurant_id',
        'status',
        'items',
        'total_price',
        'created_at',
        'updated_at'
    ];
}
