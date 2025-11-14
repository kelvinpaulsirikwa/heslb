<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BoardOfDirector;
use Illuminate\Http\Request;

class BoardOfDirectorController extends Controller
{
    public function index()
    {
        $boardMembers = BoardOfDirector::orderBy('created_at', 'desc')->get();
        return view('aboutus.partials.boardofdirector', compact('boardMembers'));
    }
}
