<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flights extends Model
{
    protected $table = 'flights';
    protected $fillable = [
        'flight_number',
        'from_code',
        'to_code',
        'departure_at',
        'price',
    ];
}
