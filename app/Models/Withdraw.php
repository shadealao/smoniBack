<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    protected $fillable = [
        'monitor_id', 'ammount', 'duration', 'payed', 'currency', 'invoice_code',
    ];

    protected $casts = [
        'payed' => 'boolean',
        'invoice_code' => 'boolean',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
