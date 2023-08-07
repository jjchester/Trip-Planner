<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = ['name', 'iata_code'];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }
}
