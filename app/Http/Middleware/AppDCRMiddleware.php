<?php

namespace App\Http\Middleware;

use Closure;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AppDCRMiddleware
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
        $token=$request->token;
        try {
            $decrypted = Crypt::decrypt($token);
            $request->request->add(['user_id'=>$decrypted]);
        } catch (DecryptException $e) {
            return response()->json(['status' => "invalid token"]);
        }

        return $next($request);
    }
}
