<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'shipto',
        'shipping_serve',
        'shipping_code',
        'shipping_cost',
        'id_order',
        'id_user',
    ];
}