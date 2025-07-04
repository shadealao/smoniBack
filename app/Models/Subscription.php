<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'service_id', 'start_date', 'end_date',
        'status', 'mode','amount','transaction_id','type_service','hour','gearbox'
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class, 'contrat_id');
    }
}
