<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class Customer extends Model
{
    use HasFactory, Notifiable, DefaultModelTrait;

    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(CustomerComment::class,'customer_id', 'id')->where('trash', 0);
    }    
}
