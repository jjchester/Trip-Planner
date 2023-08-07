<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = ['type'];

    // Add relationships to flights or any other specific columns in the trips table as needed
}
