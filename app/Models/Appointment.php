<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    use HasFactory;

    protected $fillable = [
        'learner_id',
        'instructor_id',
        'availability_id',
        'vehicle_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'status',
        'cancellation_reason',
        'price',
        'lesson_notes',
        'presence_student',
        'presence_monitor',
        'finished',
        'tag',
    ];

    protected $casts = [
        'lesson_notes' => 'array',
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // 🔁 Relations
    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
