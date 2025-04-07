<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     if (!Auth::check() || Auth::user()->role->name !== $role) {
    //         abort(403, 'Unauthorized');
    //     }
    //     return $next($request);

    // }
//     public function handle(Request $request, Closure $next, $role)
// {

//     if (!Auth::check() || !Auth::user()->hasRole($role)) {
//         abort(403, 'Unauthorized');
//     }


//     return $next($request);
// }
public function handle(Request $request, Closure $next, $role)
{
    if (!Auth::check()) {
        abort(403, 'Unauthorized - User not logged in');
    }

    // Debugging: Check user role
    // dd(Auth::user()->getRoleNames());
    // dd(Auth::user()->getAllPermissions(),auth()->user()->can('backend.dashboard'));

    // if (!Auth::user()->hasRole($role)) {
    //     abort(403, 'Unauthorized - Role mismatch');
    // }
    $user = Auth::user();
    if ($user->type !== $role) {
        abort(403, 'Unauthorized - Type mismatch');
    }

    return $next($request);
}
// public function handle(Request $request, Closure $next, $roles)
// {
//     if (!Auth::check()) {
//         return response()->json(['message' => 'Unauthorized - User not logged in'], 403);
//     }

//     $user = Auth::user();

//     // Ensure $roles is an array (explode in case of multiple roles)
//     $allowedRoles = explode('|', $roles);

//     // Debugging (Check assigned roles)
//     // dd($user->getRoleNames());

//     // Check if user has any of the allowed roles
//     if (!$user->hasAnyRole($allowedRoles)) {
//         return response()->json(['message' => 'Unauthorized - Role mismatch'], 403);
//     }

//     return $next($request);
// }



//     return $next($request);
// }


// public function handle(Request $request, Closure $next, $role)
//     {
//         if (!Auth::check()) {
//             return response()->json(['message' => 'Unauthorized - User not logged in'], 403);
//         }

//         $user = Auth::user();
//         // dd($user->getRoleNames());
//         if (!Auth::user()->hasRole($role)) {
//             return response()->json(['message' => 'Unauthorized - Role mismatch'], 403);
//         }

//         return $next($request);
//     }


// public function handle(Request $request, Closure $next, $role)
// {
//     if (!Auth::check()) {
//         abort(403, 'Unauthorized - User not logged in');
//     }

//     // Debugging: Check user role and parameter
//     // dd(Auth::user()->getRoleNames(), $role);

//     // Fix: `contains()` ka use karein
//     if (!Auth::user()->getRoleNames()->contains($role)) {
//         abort(403, 'Unauthorized - Role mismatch');
//     }

//     return $next($request);
// }





}
