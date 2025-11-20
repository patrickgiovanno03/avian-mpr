<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTandaTerima extends Model
{
    use HasFactory;

    protected $table = 'dtandaterima';

    protected $primaryKey = 'DetailID';

    public $timestamps = false;

    public function htandaterima()
    {
        return $this->belongsTo(HTandaTerima::class, 'FormID', 'FormID');
    }
}
