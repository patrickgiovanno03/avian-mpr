<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DGaji extends Model
{
    use HasFactory;
    
    protected $table = 'dgaji';

    protected $primaryKey = 'DetailID';

    public $timestamps = false;
}
