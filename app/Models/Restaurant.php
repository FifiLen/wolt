<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Restaurant extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'restaurants';
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'created_at',
        'updated_at'
    ];
}
