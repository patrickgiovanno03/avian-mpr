<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HTandaTerima extends Model
{
    use HasFactory;

    protected $table = 'htandaterima';

    protected $primaryKey = 'FormID';

    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(DTandaTerima::class, 'FormID', 'FormID')->orderBy('Idx', 'asc');
    }

    public function getNewTTNo()
    {
        $prefix = 'TT';
        $lastInvoice = self::where('TTNo', 'like', $prefix . '%')
            ->orderBy('TTNo', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->TTNo, 2);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }
}
