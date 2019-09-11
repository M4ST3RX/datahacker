<?php

namespace App\Http\Controllers;

use App\Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComputerController extends Controller
{
    public function selector()
    {
        $computers = Computer::where('player_id', Auth::id())->get();
        return view('computer.select', ['computers' => $computers]);
    }

    public function play()
    {
        
    }

    public function configure()
    {
        
    }
}
