<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearnerProfile extends Model
{
    protected $fillable = [
        'user_id', 'birth_date', 'license_type',
        'medical_certificate_expiry', 'emergency_contact_name',
        'emergency_contact_phone', 'driving_license_number',
        'theoretical_exam_passed',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'medical_certificate_expiry' => 'date',
        'theoretical_exam_passed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
