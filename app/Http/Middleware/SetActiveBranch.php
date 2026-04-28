<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetActiveBranch
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Store active branch in session; default to user's branch
            if (!session('active_branch_id')) {
                session(['active_branch_id' => $user->branch_id]);
            }
        }

        return $next($request);
    }
}
