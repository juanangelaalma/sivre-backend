<?php

namespace App\Http\Middleware;

use App\Http\Services\ResponseService;
use App\Models\Voter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VoteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->username || !$request->password) {
            return ResponseService::error(null, 'Username and password are required', 400);
        }

        $voter = Voter::where('username', $request->username)->where('password', $request->password)->first();

        if (!$voter) {
            return ResponseService::error(null, 'Invalid username or password', 400);
        }

        $request['voter'] = $voter;

        return $next($request);
    }
}
