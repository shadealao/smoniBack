<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryService extends Model
{
    protected $fillable = ['label'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_service_id');
    }
}
