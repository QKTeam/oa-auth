<?php

namespace App\Http\Controllers\ViewController\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class home extends Controller
{
    public function home (Request $request) {
        return view('admin/home')->with('links', [
            [
                'link_name' => 'Users',
                'link_url' => '/admin/user',
            ],
            [
                'link_name' => 'Links',
                'link_url' => '/admin/link',
            ],
            [
                'link_name' => 'Roles',
                'link_url' => '/admin/role'
            ],
        ]);
    }
    public function user () {
        return view('admin/user/userHome')->with('links', [
            [
                'link_name' => 'List',
                'link_url' => '/admin/user/list',
            ],
            [
                'link_name' => 'Create',
                'link_url' => '/admin/user/create',
            ],
        ]);
    }
    public function link () {
        return view('admin/link/linkHome')->with('links', [
            [
                'link_name' => 'List',
                'link_url' => '/admin/link/list',
            ],
            [
                'link_name' => 'Create',
                'link_url' => '/admin/link/create',
            ],
        ]);
    }
    public function role () {
        return view('admin/role/roleHome')->with('links', [
            [
                'link_name' => 'List',
                'link_url' => '/admin/role/list',
            ],
            [
                'link_name' => 'Create',
                'link_url' => '/admin/role/create',
            ],
        ]);
    }
}
