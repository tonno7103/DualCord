<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
        // use auth to check if user is logged in
        if (!auth()->check()) {
            return redirect($data['address'] . $data['phpPort'] . '/auth/login');
        }
        return $next($request);
    }
}
