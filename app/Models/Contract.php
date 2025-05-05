<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id', 'student_id', 'file_original', 'file_signed', 'tag', 'date',
    ];

    protected $casts = [
        'date' => 'date',
        'tag' => 'string',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
