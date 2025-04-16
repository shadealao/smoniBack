<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    protected $fillable = [
        'monitor_id', 'iban', 'bic', 'bank_name', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
