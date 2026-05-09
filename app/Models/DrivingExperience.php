<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingExperience extends Model
{
    protected $fillable = [
        'student_id', 'license_type', 'driving_experience', 
        'accompanied_by', 'driving_location', 'other_vehicle', 'experience_date'
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
