<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id', 'brand', 'model', 'year', 'plate_number',
        'fuel_type', 'insurance_expiry', 'technical_inspection_date',
        'photo_url', 'color', 'gearbox_type', 'status',
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
        'technical_inspection_date' => 'date',
        'fuel_type' => 'string',
        'gearbox_type' => 'string',
        'status' => 'string',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
