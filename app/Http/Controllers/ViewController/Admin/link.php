<?php

namespace App\Http\Controllers\ViewController\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class link extends Controller
{
    public function linkList () {
        return view('admin/link/linkList')->with('links', \App\Models\Link::all());
    }
    public function createLink () {
        return view('admin/link/create');
    }

    public function update ($link_id) {
//        return view()
    }
}
