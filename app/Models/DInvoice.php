<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DInvoice extends Model
{
    use HasFactory;

    protected $table = 'dinvoice';

    protected $primaryKey = 'DetailID';

    public $timestamps = false;
}
