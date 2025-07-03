<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id', 'ammount', 'duration', 'payed', 'currency', 'invoice_code', 'invoice_file',
    ];

    protected $casts = [
        'payed' => 'boolean',
        'ammount' => 'integer',
        'duration' => 'integer',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
