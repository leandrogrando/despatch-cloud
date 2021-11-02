<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'line_1',
        'line_2',
        'city',
        'country',
        'state',
        'postcode',
        'created_at',
        'updated_at',
    ];
}
