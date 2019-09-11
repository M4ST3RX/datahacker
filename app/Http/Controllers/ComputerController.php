<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComputerController extends Controller
{
    public function selector()
    {
        return view('computer.select');
    }
}
