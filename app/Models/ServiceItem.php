<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = ['service_id', 'label', 'status'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
