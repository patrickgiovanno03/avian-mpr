<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCustomer extends Model
{
    use HasFactory;
    
    protected $table = 'mcustomer';

    protected $primaryKey = 'CustomerID';

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(MCustomerCategory::class, 'PriceCategory', 'CategoryID');
    }
}
