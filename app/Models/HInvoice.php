<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HInvoice extends Model
{
    use HasFactory;

    protected $table = 'hinvoice';

    protected $primaryKey = 'FormID';

    public $timestamps = false;

    public function details()
    {
        return $this->hasMany(DInvoice::class, 'FormID', 'FormID');
    }

    public function getNewInvoiceNo()
    {
        $prefix = 'MC';
        $lastInvoice = self::where('InvoiceNo', 'like', $prefix . '%')
            ->orderBy('InvoiceNo', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int)substr($lastInvoice->InvoiceNo, 2);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function getNewSJNo()
    {
        $prefix = 'S';
        $lastSJ = self::where('SJNo', 'like', $prefix . '%')
            ->orderBy('SJNo', 'desc')
            ->first();

        if ($lastSJ) {
            $lastNumber = (int)substr($lastSJ->SJNo, 1);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function getTandaTerimaAttribute()
    {
        $dtandaterima = DTandaTerima::where('InvoiceNo', $this->InvoiceNo)->first();
        return $dtandaterima->htandaterima ?? null;
    }
}
