<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

/**
 * @creator: Thony Hermawan
 * @date: 31 August 2020
 */
trait AviaPermit
{
    /**
     * @param int $menu_id
     * @param string $permit
     * @return bool
     */
    public function permitted($menu_id, $permit = 'view'): bool
    {
        $user_id = $this->getAttribute($this->primaryKey);

        $mpermit = DB::table('mpermit')
            ->where('MenuID', $menu_id)
            ->where('UserID', $user_id)
            ->orderBy('CreatedDate', 'desc')
            ->first();

        if (!isset($mpermit)) return false;

        switch (strtolower($permit)) {
            case 'view': return substr($mpermit->Permit, 0, 1) == 1;
            case 'select': return substr($mpermit->Permit, 0, 1) == 1;

            case 'add': return substr($mpermit->Permit, 1, 1) == 1;
            case 'insert': return substr($mpermit->Permit, 1, 1) == 1;
            
            case 'edit': return substr($mpermit->Permit, 2, 1) == 1;
            case 'update': return substr($mpermit->Permit, 2, 1) == 1;
            
            case 'remove': return substr($mpermit->Permit, 3, 1) == 1;
            case 'delete': return substr($mpermit->Permit, 3, 1) == 1;
            
            case 'print': return substr($mpermit->Permit, 4, 1) == 1;
            case 'report': return substr($mpermit->Permit, 4, 1) == 1;
            
            case 'special': return substr($mpermit->Permit, 5, 1) == 1;
            case 'khusus': return substr($mpermit->Permit, 5, 1) == 1;
            
            case 'void': return substr($mpermit->Permit, 6, 1) == 1;
            case 'approve': return substr($mpermit->Permit, 6, 1) == 1;
            case 'approval': return substr($mpermit->Permit, 6, 1) == 1;

            case 'all': 
                $all = '';
                for ($i=0; $i < strlen($mpermit->Permit) - 1; $i++)
                    $all .= '1';
                return substr($mpermit->Permit, 0, strlen($mpermit->Permit) - 1) === $all;

            default: return substr($mpermit->Permit, 0, 1) == 1;
        }
    }

    public function hasPermit($menu_id)
    {
        $user_id = $this->getAttribute($this->primaryKey);

        $permit = DB::table('mpermit')
            ->where('MenuID', $menu_id)
            ->where('UserID', $user_id)
            ->orderBy('CreatedDate', 'desc')
            ->first();
        
        return isset($permit);
    }
}
