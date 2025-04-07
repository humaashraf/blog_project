<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect users after login based on their role.
     */
    protected function authenticated(Request $request, User $user)
    {
        // // dd($user->hasRole('admin'));
        // if ($user->hasRole('admin')) {
        //     return redirect('/admin/dashboard'); // Admin Redirect
        // } elseif ($user->hasRole('author')) {
        //     return redirect('/author/dashboard'); // Author Redirect
        // }

        // return redirect('/'); // Default fallback (Agar koi role match nahi karta)

         // Agar user ka role "author" hai to usay author-dashboard pr redirect karo
    if ($user->hasRole('author')) {
        return redirect('/author/dashboard');
    }

    // Baqi sab users ko admin dashboard pr bhejo
    return redirect('/admin/dashboard');
    }

    /**
     * Logout function
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Logout hone ke baad login page par redirect karega
    }
}
