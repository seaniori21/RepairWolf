<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class Order extends Model
{
    use HasFactory, DefaultModelTrait;

    protected $guarded = [];

    public function customer()
    {
        return $this->hasOne('App\Models\Customer','id','customer_id');
    }

    public function cashier()
    {
        return $this->hasOne('App\Models\Admin','id','cashier_id');
    }

    public function servicePerson()
    {
        return $this->hasOne('App\Models\Admin','id','service_person_id');
    }

    public function vehicle()
    {
        return $this->hasOne('App\Models\Vehicle','id','vehicle_id');
    }

    public function productItems()
    {
        return $this->hasMany(OrderProduct::class,'order_id', 'id')->where('trash', 0);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'order_id', 'id')->where('trash', 0);
    }

    // public function comments()
    // {
    //     return $this->hasMany('App\Models\OrderComment','order_id','id');
    // }

    public function comments()
    {
        return $this->hasMany(OrderComment::class,'order_id', 'id')->where('trash', 0);
    }
}
