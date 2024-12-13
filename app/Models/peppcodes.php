<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peppcodes extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tranx_date',
        'status',
        'tranx_code',
        'sender',
        'receiver',
        'principal',
        'fee',
        'total',

    ];
}
