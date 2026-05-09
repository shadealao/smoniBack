<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentIntent extends Model
{
    protected $fillable = [
        'user_id', 'ammount', 'currency', 'service_id',
    ];
}
