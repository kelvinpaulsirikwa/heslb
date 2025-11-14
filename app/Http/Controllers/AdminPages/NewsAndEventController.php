<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsAndEventController extends Controller
{
    //
    public function showallnews(){

        return view('adminpages.newsandevent.newsandevent');
    }
}
