<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'amount', 'currency', 'payment_date', 'payment_method',
        'status', 'invoice_number', 'related_appointment_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'status' => 'string',
        'payment_method' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'related_appointment_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'payment_id');
    }
}
