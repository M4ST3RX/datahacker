<?php

namespace App\Http\Controllers;

use App\Process;
use App\Response;
use Carbon\Carbon;
use App\Computer;
use App\History;
use App\TerminalCommands;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class APIController extends Controller
{
    public function terminal(Request $request){
        $request = $request->all();
        $cmd = $request['cmd'];
        $args = (isset($request['args'])) ? $request['args'] : [];
        $terminal_commands = new TerminalCommands($cmd, $args);
        return json_encode($terminal_commands->response);
    }

    function task_manager_all(){
        if(Auth::user()){
            $response = [];
            $cpu_processes = Process::where('is_cpu', true)->where('computer_id', session('computer_id'))->select('process_id as id', 'type', 'related_ip as ip')->get();
            $network_processes = Process::where('is_cpu', false)->where('computer_id', session('computer_id'))->get();
            $response["error"] = false;
            $response['cpu_data'] = $cpu_processes;
            $response['network_data'] = $network_processes;
            return json_encode($response);
        }
    }

    function task_manager_delete(Request $request){
        $id = $request->all()['id'];
        $response = [];
        $process = Process::find($id);
        if($process){
            if($process->getOriginal()['computer_id'] == session('computer_id')){
                Process::destroy($id);
                $response["error"] = false;
                $response["msg"] = "Process removed.";
            }
        } else {
            $response["error"] = true;
            $response["msg"] = "No process found with that ID.";
        }

        return json_encode($response);
    }

    function task_manager_finish(Request $request){
        $id = $request->all()['id'];
        $response = [];
        $process = Process::find($id);
        if($process){
            if($process->computer_id == session('computer_id')){
                if(Carbon::parse($process->end_time)->isPast()){
                    switch ($process->type) {
                        case 'crack':
                             $responseObj = new Response();
                             $response = $responseObj->crack($process);
                             break;
                        default:
                            break;
                    }
                } else {
                    $response["error"] = true;
                    $response["msg"] = "This process is not finished yet.";
                }
            }
        } else {
            $response["error"] = true;
            $response["msg"] = "No process found with that ID.";
        }
        return json_encode($response);
    }

    public function getFiles(){
        $response = [];
        if(Auth::user()){
            $computer = Computer::find(session('computer_id'));
            foreach ($computer->files as $file) {
                $file->type;
            }
            $response['error'] = false;
            $response['files'] = json_encode($computer->files);
        } else {
            $response['error'] = true;
            $response['msg'] = "You are not logged in.";
        }
        return $response;
    }

    public function getHistoryList(){
        $response = [];
        if(Auth::user()){
            $history = History::where('computer_id', session('computer_id'))->get();
            $computer_list = [];
            $list = [];
            foreach ($history as $value) {
                $computer_list[] = $value->cracked_id;
            }
            $computers = Computer::whereIn('computer_id', $computer_list)->leftJoin('npcs', 'computers.npc_id', '=', 'npcs.id')->get();
            foreach ($computers as $value) {
                $temp_list = [];
                $temp_list['ip'] = $value->ip;
                $temp_list['name'] = $value->name;
                $temp_list['pass'] = $value->pass;
                $temp_list['action'] = "-";
                $list[] = $temp_list;
            }
            $response['error'] = false;
            $response['list'] = $list;
        }
        return json_encode($response);
    }

    public function addToHistoryList(Request $request){
        $ip = $request->all()['ip'];
        $response = [];
        if(Auth::user()){
            $computer = Computer::where('ip', $ip)->first();
            if($computer->npc_id == null){

            } else {
                $npc = DB::table("npcs")->where('id', $computer->npc_id)->first();
                $list = [
                    'ip' => $ip,
                    'name' => $npc->name,
                    'pass' => $computer->pass,
                    'action' => '-'
                ];
            }
            $response['error'] = false;
            $response['list'] = $list;
        }
        return json_encode($response);
    }
}
