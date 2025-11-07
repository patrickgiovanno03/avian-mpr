<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSingle extends Model
{
    use HasFactory;

    protected $table = 'msingle';

    protected $primaryKey = 'SingleID';

    public $timestamps = false;

    public function pricesingle()
    {
        return $this->hasMany(MPriceSingle::class, 'Category', 'Category');
    }
}
