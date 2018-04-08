<?php

namespace App\Http\Controllers;

use App\Mail\generatePassword;
use App\Mail\resetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    static public function sendPassword ($password, $to, $username) {
        $message = [
            'password' => $password,
            'username' => $username
        ];
        Mail::to($to)->send(new generatePassword($message));
    }
    static public function sendResetPassword ($to, $password) {
        $message = [
            'password' => $password,
        ];
        Mail::to($to)->send(new resetPassword($message));
    }
}
