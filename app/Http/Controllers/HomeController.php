<?php

namespace App\Http\Controllers;

use App\Computer;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $computer = Computer::where('player_id', Auth::id())->first();
        if(!is_null($computer)){
            session(['computer_ip' => $computer->ip]);
            session(['computer_id' => $computer->computer_id]);
            session(['computer_pass' => $computer->pass]);
            session(['connectedTo' => "local"]);
        }
        return view('home', ['os' => $computer->operating_system]);
    }
}
