<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategoryService extends Model
{
    protected $fillable = ['label'];

    public function services()
    {
        return $this->hasMany(Service::class, 'sub_category_service_id');
    }
}
