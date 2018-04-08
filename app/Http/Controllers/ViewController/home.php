<?php

namespace App\Http\Controllers\ViewController;

use App\Models\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class home extends Controller
{
    public function home (Request $request) {
        $links = Link::all()->toArray();
        unset($links[0]);
        unset($links[1]);
        return view('home')->with('links', $links);
    }
}
