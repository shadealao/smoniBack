<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['user_id', 'amount', 'currency', 'payment_date', 'payment_method', 'status', 'invoice_number', 'related_appointment_id', 'related_subscription_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'payment_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'related_appointment_id');
    }
}
