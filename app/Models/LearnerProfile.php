<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearnerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'birthdate',
        'city',
        'address',
        'postal_code',
        'cin_number',
        'cin_issue_date',
        'cin_issue_place',
        'permit_number',
        'permit_issue_date',
        'permit_category'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'cin_issue_date' => 'date',
        'permit_issue_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
