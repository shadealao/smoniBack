<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingPoint extends Model
{
    protected $fillable = [
        'monitor_id', 'name', 'address', 'city', 'long', 'lat', 'is_active',
    ];

    protected $casts = [
        'address' => 'array',
        'is_active' => 'boolean',
        'long' => 'decimal:8',
        'lat' => 'decimal:8',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
