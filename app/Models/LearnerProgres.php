<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearnerProgres extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'learner_id', 'step_item_id'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function stepItem()
    {
        return $this->belongsTo(StepModuleItem::class, 'step_item_id');
    }
}
