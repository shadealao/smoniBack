<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id', 'iban', 'bic', 'bank_name', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
