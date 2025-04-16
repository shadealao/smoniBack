<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorProfile extends Model
{
    protected $fillable = [
        'user_id', 'certification_number', 'certification_expiry',
        'hourly_rate', 'bio', 'years_of_experience',
        'specialization', 'is_independent', 'solde',
    ];

    protected $casts = [
        'certification_expiry' => 'date',
        'hourly_rate' => 'decimal:2',
        'is_independent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
