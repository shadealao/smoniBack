<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',  'specialty',
        'bio',
        'address',
        'city',
        'postal_code',
        'solde',
        'certification_number',
        'certification_issue_date',
        'hourPrice',
        'hourDiscount',
        'tva',

        'juridic_form',
        'siret',
        'num_activity',
        'num_tva',
        'num_teach_authorization',
        'date_teach_permit',
        'date_medical_visit'
    ];

    protected $casts = [
        'certification_issue_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
