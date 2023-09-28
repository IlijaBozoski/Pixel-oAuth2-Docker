<?php


namespace App;

use Illuminate\Support\Facades\DB;

class QueryClass
{
    public static function getClients($user_id)
    {
        return DB::table('oauth_clients')
            ->select('name', 'redirect')
            ->where('user_id', $user_id)
            ->get();
    }

}
