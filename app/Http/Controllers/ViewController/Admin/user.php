<?php

namespace App\Http\Controllers\ViewController\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class user extends Controller
{
    public function userList () {
        $roles = Role::where('id', '>', '0')->orderBy('id', 'asc')->get();
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->gender === 1) $user->gender = '男';
            else $user->gender = '女';
            $user->role_name = $user->role->name;
        }
        return view('admin/user/userList')->with('users', $users->toArray());
    }
}
