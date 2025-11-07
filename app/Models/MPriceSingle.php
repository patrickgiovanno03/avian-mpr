<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPriceSingle extends Model
{
    use HasFactory;
    
    protected $table = 'mpricesingle';

    protected $primaryKey = 'PriceID';

    public $timestamps = false;
}