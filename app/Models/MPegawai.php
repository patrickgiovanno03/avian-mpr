<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MPegawai extends Model
{
    use HasFactory;

    protected $table = 'mpegawai';

    protected $primaryKey = 'PegawaiID';

    public $timestamps = false;

    // protected $casts = [
    //     'IsDel' => 'bool',
    //     'CreatedAt' => 'datetime',
    //     'UpdateAt' => 'datetime',
    // ];

    // public function muser()
    // {
    //     return $this->hasOne('App\Models\MUser', 'PegawaiID', 'PegawaiID');
    // }
}
