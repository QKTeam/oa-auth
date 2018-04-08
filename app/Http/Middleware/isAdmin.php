<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
                if ($user->id !== 1) abort(403);
                return $next($request);
            }
        }
        $token = $request->input('token', null);
        if($token === null) {
            $token_head = $request->header('token');
            if($token_head === null) abort(401);
            $token = $token_head;
        }
        $apiToken = ApiToken::where('token', '=', $token)->first();
        if($apiToken === null) abort(401);
        $user = User::where('id', '=', $apiToken->user_id)->first();
        if($user === null) abort(404);
        if($apiToken->expired_at === null || $apiToken->expired_at >= Carbon::now()) {
            $apiToken->expired_at = $apiToken->expired_at === null ? null : Carbon::now()->addMinutes(30);
            $apiToken->save();
            if ($user->id !== 1) abort(403);
            return $next($request);
        }
        return abort(401);
    }
}
