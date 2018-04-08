<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Validator;

class UserController extends Controller
{
    private function generatePassword ($length = 16) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        return $password;
    }

    public function delete (Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $user = User::find($request->input('user_id'));
        if ($user->id === 1) return response('Can not delete admin', 403);
        $user->delete();
        return response([
            'status' => 1,
        ]);
    }
    public function reset (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'string|required',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $user = null;
        $user = User::where('username', $request->input('username'))
            ->orWhere('email', $request->input('username'))->first();
        if ($user === null) return response('User Not Found', 404);
        $password = $this->generatePassword();
        MailController::sendResetPassword($user->email, $password);
        $user->password = bcrypt(sha1($password));
        $user->save();
        return response([
            'status' => 1,
        ]);
    }
    public function change (Request $request, $user_id) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|size:40',
            'phone' => 'nullable|regex:/^\d{11}$/',
            'gender' => 'integer|in:0,1',
            'new_password' => 'nullable|string|size:40',
            'role_id' => 'integer',
        ]);
        if ($validator->fails()) return response($validator->errors(), 422);
        $now_user = $request->input('now_user', null);
        if ($now_user === null) return response('Unauthorized', 401);
        if ($now_user->id !== 1 && $now_user->id !== +$user_id) return response('Forbidden', 403);
        $change_user = User::find($user_id);
        if ($change_user === null) return response('User Not Found', 404);
        if (!app('hash')->check($request->input('password'), $now_user->password)) return response('Unauthorized', 401);
        $change_user->phone = $request->input('phone', $change_user->phone);
        $change_user->gender = $request->input('gender', $change_user->gender);
        $change_user->role_id = $request->input('role_id', $change_user->role_id);
        $change_user->email = $request->input('email', $change_user->email);
        $new_password = $request->input('new_password', null);
        if ($new_password) $change_user->password = bcrypt($new_password);

        $change_user->save();
        return response([
            'status' => 1,
        ]);
    }
}
