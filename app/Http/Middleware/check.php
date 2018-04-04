<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use App\Models\User;
use Carbon\Carbon;
use Closure;

class check
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $remember_token = $request->input('remember_token', null);
        if ($remember_token === null) {
            $remember_token_head = $request->header('remember_token');
            if ($remember_token_head !== null) $remember_token = $remember_token_head;
        }
        if ($remember_token !== null) {
            $user = User::where('remember_token', $remember_token)->first();
            if ($user) {
                $request['remember_token'] = $remember_token;
                $request['now_user'] = $user;
                return $next($request);
            }
        }
        $token = $request->input('token', null);
        if($token === null) {
            $token_head = $request->header('token');
            if($token_head === null) return $next($request); // response('Unauthorized', 401);
            $token = $token_head;
        }
        $apiToken = ApiToken::where('token', '=', $token)->first();
        if($apiToken === null) return $next($request); // response('Unauthorized', 401);
        $user = User::where('id', '=', $apiToken->user_id)->first();
        if($user === null) return $next($request); // response('User Not Found', 404);
        if($apiToken->expired_at === null || $apiToken->expired_at >= Carbon::now()) {
            $apiToken->expired_at = $apiToken->expired_at === null ? null : Carbon::now()->addMinutes(30);
            $apiToken->save();
            $request['token'] = $token;
            $request['now_user'] = $user;
            return $next($request);
        }
        return $next($request);
    }
}
