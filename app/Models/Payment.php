<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class Payment extends Model
{
    use HasFactory, DefaultModelTrait;

    protected $guarded = [];

    public function order()
    {
        return $this->hasOne('App\Models\Order','id','order_id');
    }

    public function paymentType()
    {
        return $this->hasOne('App\Models\PaymentType','id','payment_type_id');
    }
}
