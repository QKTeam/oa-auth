<?php

namespace App\Http\Controllers\ViewController\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class home extends Controller
{
    public function home () {
        return view('admin/home')->with('links', [
            [
                'link_name' => 'User',
                'link_url' => '/admin/user',
            ],
        ]);
    }
    public function user () {
        return view('admin/user/userHome')->with('links', [
            [
                'link_name' => 'List',
                'link_url' => '/admin/user/list',
            ],
        ]);
    }
}
