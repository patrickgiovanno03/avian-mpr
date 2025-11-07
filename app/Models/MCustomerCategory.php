<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCustomerCategory extends Model
{
    use HasFactory;

    protected $table = 'mcustomercategory';

    protected $primaryKey = 'CategoryID';

    public $timestamps = false;
}
