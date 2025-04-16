<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'owner_id', 'brand', 'model', 'year', 'gearbox_type',
        'plate_number', 'last_maintenance_date', 'next_maintenance_date',
        'status', 'insurance_expiry', 'technical_inspection_date', 'photo_url',
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'insurance_expiry' => 'date',
        'technical_inspection_date' => 'date',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
