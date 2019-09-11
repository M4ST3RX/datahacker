<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Game extends Model
{
    public static function generateUserIP(){
        $ip = "" . rand(1, 255);
        for($x = 0; $x < 3; $x++){
            $ip .= "." . rand(1, 255);
        }
        if(!Game::checkUniqueIP($ip)){
            Game::generateUserIP();
        }
        return $ip;
    }

    public static function checkUniqueIP($ip){
        $npcs = DB::table('computers')->get();
        $reserved_ips = ['1.1.1.1'];
        foreach ($npcs as $npc) {
            array_push($reserved_ips, $npc->ip);
        }
        if(!in_array($ip, $reserved_ips)) return true;
        return false;
    }

    public static function generatePassword($length = 16){
        return Str::random($length);
    }
}
