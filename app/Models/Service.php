<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['category_service_id', 'sub_category_service_id', 'title', 'price'];

    public function category()
    {
        return $this->belongsTo(CategoryService::class, 'category_service_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategoryService::class, 'sub_category_service_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
