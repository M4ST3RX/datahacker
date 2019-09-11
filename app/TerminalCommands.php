<?php

namespace App;

use App\Computer;
use App\Process;
use App\File;
use App\FileType;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class TerminalCommands extends Model
{

    public $response = [];
    protected $allowedCommands = ["ssh", "crack", "exit", "make"];
    protected $makeTypes = [];

    public function __construct($command, $args){
        if($command != '?'){
            if(in_array($command, $this->allowedCommands)){
                $func = "command_" . $command;
                $this->$func($args);
            } else {
                $this->response["error"] = true;
                $this->response["msg"] = "Unknown command.";
            }
        } else {

        }
    }

    public function command_make($args){
        $types = FileType::all();
        foreach ($types as $item) {
            $this->makeTypes[$item->make_type] = ['id' => $item->id, 'admin' => $item->admin_only];
        }
        if(isset($args[0]) && isset($args[1])){
            $type = $args[0];
            $level = $args[1];
            $name = join(' ', array_splice($args, 2));
            if(array_key_exists($type, $this->makeTypes)){
                if(Auth::user()->admin >= $this->makeTypes[$type]['admin']){
                    // WARNING: $name has to be escaped before going live!!!!
                    $file = File::firstOrNew(['file_type' => $this->makeTypes[$type]['id'], 'file_name' => $name, 'file_level' => $level]);
                    $file->save();
                    DB::table('computer_files')->insert([
                        [
                            'computer_id' => session('computer_id'),
                            'file_id' => $file->id
                        ]
                    ]);
                    $this->response["error"] = false;
                    $this->response["msg"] = "'" . $name . "' file has been created";
                } else {
                    $this->response["error"] = true;
                    $this->response["msg"] = "You don't have permission to make this file.";
                }
            } else {
                $this->response["error"] = true;
                $this->response["msg"] = "Unknown type";
            }
        } else {
            $this->response["error"] = true;
            $this->response["msg"] = "Syntax error";
        }
    }

    public function command_ssh($args){
        if(isset($args[0]) && isset($args[1])){
            $ip = $args[0];
            $pass = $args[1];
            if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $ip)){
                $search = Computer::where('ip', $ip)->where('pass', $pass)->first();
                if(is_null($search)){
                    $this->response["error"] = true;
                    $this->response["msg"] = "\"$ip\" is not responding";
                } else {
                    session(['connectedTo' => $ip]);
                    $this->response["error"] = false;
                    $this->response["msg"] = "Connected to " . $ip;
                    $this->response['function'] = "setConnectedTo";
                    $this->response['ip'] = $ip;
                }
            } else {
                $this->response["error"] = true;
                $this->response["msg"] = "Invalid IP format.";
            }
        } else {
            $this->response["error"] = true;
            $this->response["msg"] = "Syntax error.";
        }
    }

    public function command_exit($args){
        if(session(['connectedTo']) != "local"){
            session(['connectedTo' => "local"]);
            $this->response["error"] = false;
            $this->response["msg"] = "Disconnected";
            $this->response['function'] = "setConnectedTo";
            $this->response['ip'] = "local";
        } else {
            $this->response["true"] = false;
            $this->response["msg"] = "You are not logged in to any computer.";
        }
    }

    public function command_crack($args){
        if(isset($args[0])){
            $ip = $args[0];
            if(preg_match("/[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $ip)){
                $search = Computer::where('ip', $ip)->first();
                if(is_null($search)){
                    $this->response["error"] = true;
                    $this->response["msg"] = "\"$ip\" is not responding";
                } else {
                    if($search->computer_id == session('computer_id')){
                        $this->response["error"] = false;
                        $this->response["msg"] = "Your password: " . $search->pass;
                    } else {
                        $cracked = DB::table('history')->where('computer_id', session('computer_id'))->where('cracked_id', $search->computer_id)->first();
                        if($cracked){
                            $this->response["error"] = false;
                            $this->response["msg"] = "Known password: " . $search->pass;
                        } else {
                            $process = new Process();
                            $process->setData(session('computer_id'), $ip, 'crack', json_encode([]), 10, true);
                            $process_id = $process->saveProcess();
                            $this->response["error"] = false;
                            $this->response["process_cpu"] = true;
                            $this->response["process_data"] = ["id" => $process_id, "ip" => $ip, "time" => 10, 'type' => 'CRC'];
                            $this->response["msg"] = "Cracking password for <span style='font-weight: bolder;'>" . $ip . "</span>";
                        }
                    }
                }
            } else {
                $this->response["error"] = true;
                $this->response["msg"] = "Invalid IP format.";
            }
        } else {
            $this->response["error"] = true;
            $this->response["msg"] = "Syntax error.";
        }
    }
}
