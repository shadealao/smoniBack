<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'duration_hours', 'required_for_license',
        'display_order', 'file', 'is_active',
    ];

    protected $casts = [
        'required_for_license' => 'boolean',
        'is_active' => 'boolean',
        'duration_hours' => 'integer',
        'display_order' => 'integer',
    ];

    public function steps()
    {
        return $this->hasMany(ModuleStep::class, 'module_id');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(LearnerProgres::class, 'module_id');
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class, 'module_id');
    }
}
