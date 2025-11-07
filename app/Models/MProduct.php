<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProduct extends Model
{
    use HasFactory;

    protected $table = 'mproduct';

    protected $primaryKey = 'ProductID';

    public $timestamps = false;
    
    protected $fillable = [
        'Name',
        'Satuan',
        'Kode'
    ];

    public function price()
    {
        return $this->hasOne(MPrice::class, 'ProductID', 'ProductID');
    }
}
