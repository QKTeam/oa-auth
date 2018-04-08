<?php

namespace App\Http\Controllers\ViewController;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Validator;

class login extends Controller
{
    public function needLogin (Request $request) {
        return view('login')->with('value', [
            'from' => $request->input('name', 'portal'),
            'from_url' => $request->input('callback', env('APP_URL')),
        ]);
    }
    public function resetPassword (Request $request) {
        return view('resetPassword');
    }
    public function updateProfile (Request $request) {
        $user = $request->input('now_user', null);
        if ($user === null) return response('Unauthorized', 401);
        $roles = Role::all()->toArray();
        return view('profile')->with('user', $user)->with('roles', $roles);
    }
}
