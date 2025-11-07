<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPrice extends Model
{
    use HasFactory;
    
    protected $table = 'mprice';

    protected $primaryKey = 'PriceID';

    public $timestamps = false;

    protected $fillable = [
        'ProductID',
        'PriceKonsumen',
        'PriceSup1',
        'PriceSup2',
        'PriceDistributor',
    ];

    public function product()
    {
        return $this->belongsTo(MProduct::class, 'ProductID', 'ProductID');
    }
}
