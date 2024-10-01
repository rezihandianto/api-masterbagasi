<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'description',
        'discount',
        'is_active',
        'activation_time',
        'expiration_time',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
