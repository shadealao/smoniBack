<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_service_id', 'title', 'price', 'type', 'time', 'hour'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryService::class, 'category_service_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceItem::class, 'service_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
