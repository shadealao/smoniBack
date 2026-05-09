<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'monitor_id', 'registration_date', 'status',
        'result_score', 'instructor_comments',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'status' => 'string',
        'result_score' => 'decimal:2',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }
}
