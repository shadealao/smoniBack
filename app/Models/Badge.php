<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'module_id','list_badge_id', 'awarded_at', 'validation_instructor_id', 'certification_url',
    ];

    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }

    public function list_badge()
    {
        return $this->belongsTo(ListBadge::class, 'list_badge_id');
    }

    public function validationInstructor()
    {
        return $this->belongsTo(User::class, 'validation_instructor_id');
    }
}
