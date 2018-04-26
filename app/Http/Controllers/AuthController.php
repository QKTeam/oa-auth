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
    private function generatePassword ($length = 16) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        return $password;
    }
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
            'email' => 'required|email|unique:user,email',
            'role_id' => 'required|integer',
            'gender' => 'integer|in:0,1',
            'name' => 'string',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);

        $user = new User();
        $user->username = $request->input('username');
        $password = $this->generatePassword();
        MailController::sendPassword($password, $request->input('email'), $user->username);
        $user->password = bcrypt(sha1($password));
        $user->role_id = $request->input('role_id');
        $user->email = $request->input('email');
        $user->gender = $request->input('gender', 1);
        $user->name = $request->input('name', $user->username);
        $user->save();
        return response([
            'status'=> 1,
        ]);
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
    public function isAdmin (Request $request) {
        if ($request['now_user'] && $request['now_user']->id === 1) return response([
            'status' => 1,
        ]);
        else return response([
            'status' => 0,
        ]);
    }
    public function getAuth (Request $request) {
        $user = $request->input('now_user', null);
        if ($user === null) return response([]);
        return response([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => [
                'id' => $user->role->id,
                'name' => $user->role->name,
                'short_name' => $user->role->short_name,
                'icon' => $user->role->icon,
                'color' => $user->role->color,
            ],
            'phone' => $user->phone,
            'name' => $user->name,
            'gender' => $user->gender,
        ]);
    }
}
