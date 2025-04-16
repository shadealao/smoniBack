<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDoc extends Model
{
    protected $fillable = [
        'user_id', 'name', 'file', 'file_type', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
