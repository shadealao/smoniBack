<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = ['subscription_id', 'student_id', 'file_original', 'file_signed', 'tag', 'date'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
