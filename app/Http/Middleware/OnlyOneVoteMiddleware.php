<?php

namespace App\Http\Middleware;

use App\Http\Services\ResponseService;
use App\Models\Vote;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlyOneVoteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Vote::where('voter_id', $request->voter->id)->first()) {
            return ResponseService::error(
                null,
                'You have already voted',
                400
            );
        }

        return $next($request);
    }
}
