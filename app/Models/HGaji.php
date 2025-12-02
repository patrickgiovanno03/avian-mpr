<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HGaji extends Model
{
    use HasFactory;

    protected $table = 'hgaji';

    protected $primaryKey = 'HeaderID';

    public $timestamps = false;

    public function dgaji()
    {
        return $this->hasMany(DGaji::class, 'HeaderID', 'HeaderID');
    }

    public function pegawai()
    {
        return $this->belongsTo(MPegawai::class, 'PegawaiID', 'PegawaiID');
    }

    public function mgaji()
    {
        return $this->belongsTo(MGaji::class, 'GajiID', 'GajiID');
    }

    public function getTotal()
    {
        return $this->dgaji()->sum('Pokok') + $this->dgaji()->sum('Lembur') + $this->Bonus + $this->UangMakan;
    }
}