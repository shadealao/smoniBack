<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id', 'student_id', 'module_id', 'module_step_id', 'comment', 'date',
    ]; 

    protected $casts = [
        'date' => 'date',
    ];

    public function monitor()
    {
        return $this->belongsTo(User::class, 'monitor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function moduleStep()
    {
        return $this->belongsTo(ModuleStep::class, 'module_step_id');
    }
}
