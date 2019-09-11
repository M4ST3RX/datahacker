<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Process;
use App\Computer;
use Illuminate\Support\Facades\DB;

class Response extends Model
{
    function crack(Process $process){
        $response = [];
        $cracked = Computer::where('ip', $process->related_ip)->first();
        if(DB::table('history')->where('computer_id', $process->computer_id)->where('cracked_id', $cracked->computer_id)->first()){
            $response["error"] = false;
            $response["msg"] = "Known password: " . $cracked->pass;
            return $response;
        }
        DB::table('history')->insert([
            'computer_id' => $process->computer_id,
            'cracked_id' => $cracked->computer_id
        ]);
        $response['error'] = false;
        $response['msg'] = "The password for " . $process->related_ip . " is " . $cracked->pass;
        $response['last_process'] = $process;
        $process->delete();
        return $response;
    }
}
