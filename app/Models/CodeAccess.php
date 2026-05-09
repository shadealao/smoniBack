<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CodeAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'liens', 'identifiant', 'password'
    ];
}
