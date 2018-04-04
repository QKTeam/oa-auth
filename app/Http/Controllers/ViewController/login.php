<?php

namespace App\Http\Controllers\ViewController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class login extends Controller
{
    public function needLogin (Request $request) {
        return view('login')->with('value', [
            'from' => $request->input('name', 'portal'),
            'from_url' => $request->input('callback', env('APP_URL')),
        ]);
    }
}
