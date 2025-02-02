<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('games.index');
    }

    public function pingPong()
    {
        return view('games.ping-pong');
    }

    public function rockPaperScissors()
    {
        return view('games.rock-paper-scissors');
    }
}
