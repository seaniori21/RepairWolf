<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class AllowedIpAddress extends Model
{
    use HasFactory, DefaultModelTrait;

    protected $guarded = [];
}
