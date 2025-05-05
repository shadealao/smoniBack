<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'plan_id', 'start_date', 'end_date', 'type_service',
        'status', 'auto_renewal', 'payment_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'type_service' => 'string',
        'status' => 'string',
        'auto_renewal' => 'boolean',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function plan()
    {
        return $this->belongsTo(Service::class, 'plan_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'subscription_id');
    }
}
