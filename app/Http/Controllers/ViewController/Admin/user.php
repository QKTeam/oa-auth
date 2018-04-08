<?php

namespace App\Http\Controllers\ViewController\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class user extends Controller
{
    public function userList () {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->gender === 1) $user->gender = '男';
            else $user->gender = '女';
            $user->role_name = $user->role->name;
        }
        return view('admin/user/userList')->with('users', $users->toArray());
    }

    public function createUser () {
        $roles = Role::all();
        return view('admin/user/create')->with('roles', $roles);
    }

    public function update ($user_id) {
        $user = \App\Models\User::find($user_id);
        $roles = Role::all();
        return view('admin/user/update')->with('user', $user)->with('roles', $roles);
    }
}
