<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\Link;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Ramsey\Uuid\Uuid;


class AuthController extends Controller
{
    private function check ($link, $from) {
        if (strpos($from, $link) === false) return false;
        return true;
    }

    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'unique:user,username',
                'regex:/^[A-Za-z][A-Za-z0-9_\.]{5,19}$/',
            ],
            'password' => 'required|string|size:40',
            'email' => 'required|email|unique:user,email',
            'role_id' => 'required|integer',
            'gender' => 'integer|in:0,1',
            'name' => 'string',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $user = new User();
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->role_id = $request->input('role_id');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender', 1);
        $user->name = $request->input('name', $user->username);
        $user->save();
        return view('home');
    }
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'string|required',
            'password' => 'required|string|size:40',
            'from' => 'required|string',
            'from_url' => 'required|string',
        ]);

//        echo $_SERVER["HTTP_REFERER"] . "\n";

        if ($validator->fails()) return response($validator->errors(), 422);
        $user = null;
//        if ($request->input('username', null)) {
//            $user = User::where('username', $request->input('username'))->first();
//        } else if ($request->input('email', null)) {
//            $user = User::where('email', $request->input('email'))->first();
//        }
        $user = User::where('username', $request->input('username'))
            ->orWhere('email', $request->input('username'))->first();
        if ($user === null) return response('User Not Found', 404);
        if (!app('hash')->check($request->input('password'), $user->password)) return response('Unauthorized', 401);
        $api_token = new ApiToken();
        $api_token->token = Uuid::uuid4();
        $from_url = $request->input('from_url');
        $from = $request->input('from');
        $links = Link::all();
        $flag = false;
        foreach ($links as $link) {
            if ($this->check($link->link_url, $from_url) && $from === $link->link_name) {
                $flag = true;
                break;
            }
        }
        if (!$flag) return response('From Not Allowed', 401);
        if($request->input('remember', 'false') === 'true') {
            $api_token->expired_at = null;
            $user->remember_token = Uuid::uuid4();
            $user->save();
        }
        else {
            $api_token->expired_at = Carbon::now()->addMinutes(30);
            $user->remember_token = null;
            $user->save();
        }
        $api_token->user_id = $user->id;
        $api_token->save();
        return response([
            'token' => $api_token->token,
            'remember_token' => $user->remember_token,
        ]);
    }
    public function checkStatus (Request $request) {
        if ($request['now_user']) return response([
            'status' => 1,
        ]);
        else return response([
            'status' => 0,
        ]);
    }
}