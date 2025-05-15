<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleKnowledge extends Model
{
    protected $fillable = [
        'student_id', 'steering', 'clutch', 'gearbox', 'braking'
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
