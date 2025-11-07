<?php

namespace App\Models;

use App\Traits\AviaPermit;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MUser extends Authenticatable
{
    use Notifiable, AviaPermit;
    
    protected $table = 'muser';

    protected $primaryKey = 'UserID';

    public $timestamps = false;

    protected $hidden = ['Passwd'];

    protected $casts = [
        'IsAD' => 'boolean',
        'IsDel' => 'boolean',
        'CreatedAt' => 'datetime',
        'UpdateAt' => 'datetime',
    ];

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
            parent::setAttribute($key, $value);
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\MPegawai', 'PegawaiID', 'PegawaiID');
    }
}
