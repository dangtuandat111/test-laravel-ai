<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'id',
        'flight_id',
        'customer_name',
        'status',
        'total_price',
        'created_at',
        'updated_at',
    ];
}
