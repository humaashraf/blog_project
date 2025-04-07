<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;

use Illuminate\Http\Request;



class AdminDashboardController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'redirect.non.authors']);
    // }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('backend.dashboard');
    }
//     public function showSettings()
// {
//     $settings = Setting::first(); // Pehla record fetch karega
//     return view('backend.admin.dashboard', compact('settings'));
// }

}
