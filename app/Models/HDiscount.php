<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HDiscount extends Model
{
    use HasFactory;

    protected $table = 'hdiscount';

    protected $primaryKey = 'DiscountID';

    public $timestamps = false;
}
