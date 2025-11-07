<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MGaji extends Model
{
    use HasFactory;

    protected $table = 'mgaji';

    protected $primaryKey = 'GajiID';

    public $timestamps = false;

    public function hgaji()
    {
        return $this->hasMany(HGaji::class, 'GajiID', 'GajiID');
    }
}
