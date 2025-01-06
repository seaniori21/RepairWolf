<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class Vehicle extends Model
{
    use HasFactory, DefaultModelTrait;

    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }
}
