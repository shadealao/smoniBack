<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'student_id', 'instructor_id', 'attitude_control_priority', 'attitude_learning_desire',
        'installation', 'start_stop', 'steering_control', 'comprehension', 'memory',
        'trajectory', 'orientation', 'observation', 'gaze', 'emotivity', 'tension',
        'partial_results', 'final_result', 'theory_hours', 'practice_hours',
        'gearbox_type', 'proposal_accepted'
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
