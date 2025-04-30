<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id', 'student_id', 'appointment_id', 'duration',
        'intervention_date', 'invoiced', 'status',
    ];

    protected $casts = [
        'invoiced' => 'boolean',
        'status' => 'boolean',
        'duration' => 'integer',
        'intervention_date' => 'date',
    ];

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
