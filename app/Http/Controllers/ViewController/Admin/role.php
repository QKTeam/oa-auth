<?php

namespace App\Http\Controllers\ViewController\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class role extends Controller
{
    public function roleList () {
        return view('admin/role/roleList')->with('roles', \App\Models\Role::all());
    }
    public function createRole () {
        return view('admin/role/create');
    }

    public function update ($role_id) {
//        return view()
    }
}
