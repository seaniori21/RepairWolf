<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\DefaultModelTrait;

class OrderComment extends Model
{
    use HasFactory, DefaultModelTrait;

    protected $guarded = [];
}
