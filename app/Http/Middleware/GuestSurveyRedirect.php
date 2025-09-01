<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GuestSurveyRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            Log::info('User is not authenticated, redirecting to login with survey_redirect flag.');
            return redirect()->route('login')->with('survey_redirect', true);
        }

        Log::info('User is authenticated, proceeding to the survey.');

        return $next($request);
    }
}
