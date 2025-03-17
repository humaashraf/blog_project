<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;
use App\Models\Post; // Post Model Include Karo


// use Illuminate\Support\Facades\Session; // Session Facade Include

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

//     public function handle(Request $request, Closure $next)
// {
//     $postId = $request->route('id'); // Route se 'id' lo

//     if ($postId) {
//         Visitor::create([
//             'post_id' => $postId,
//             'visitor_ip' => $request->ip(),
//         ]);
//     }

//     return $next($request);
// }



// public function handle(Request $request, Closure $next)
// {
//     die("Middleware executed!"); // Debugging
// }

// public function handle(Request $request, Closure $next)
// {
//     $postId = $request->route('id'); // Route se ID lo

//     Visitor::create([
//         'post_id' => $postId,
//         'visitor_ip' => $request->ip(),
//     ]);

//     return $next($request);
// }


    // public function handle(Request $request, Closure $next)
    // {
    //     $postId = $request->route('id'); // Route se ID lo
    //     $visitorIp = $request->ip();

    //     // ✅ Duplicate check: Sirf ek entry per refresh allow karni hai
    //     $existingVisitor = Visitor::where('visitor_ip', $visitorIp)
    //         ->where('post_id', $postId)
    //         ->where('created_at', '>=', now()->subSeconds(5)) // 5 sec me ek hi entry
    //         ->first();

    //     if (!$existingVisitor) {
    //         Visitor::create([
    //             'post_id' => $postId,
    //             'visitor_ip' => $visitorIp,
    //         ]);
    //     }

    //     return $next($request);
    // }

    // public function handle(Request $request, Closure $next)
    // {
    //     $postId = $request->route('id'); // ✅ Route se ID lo
    //     $visitorIp = $request->ip();

    //     // ✅ Page Name Set Karein
    //     $pageName = $postId ? "Post Page" : "Home Page";

    //     // ✅ Duplicate check: Sirf ek entry per refresh allow karni hai
    //     $existingVisitor = Visitor::where('visitor_ip', $visitorIp)
    //         ->where('post_id', $postId)
    //         ->where('page_name', $pageName)
    //         ->where('created_at', '>=', now()->subSeconds(5)) // ✅ 5 sec me ek hi entry
    //         ->first();

    //     if (!$existingVisitor) {
    //         Visitor::create([
    //             'post_id' => $postId,
    //             'visitor_ip' => $visitorIp,
    //             'page_name' => $pageName, // ✅ Page Name Save Karein
    //         ]);
    //     }

    //     return $next($request);
    // }

    // public function handle(Request $request, Closure $next)
    // {
    //     $postId = $request->route('id'); // ✅ Route se ID lo
    //     $visitorIp = $request->ip();

    //     // Page Name Set Karein
    //     if ($request->is('/')) {
    //         $pageName = "Home Page";
    //     } elseif ($request->is('about')) {
    //         $pageName = "About Page";
    //     } elseif ($request->is('contact')) {
    //         $pageName = "Contact Page";
    //     } elseif ($request->is('blog')) {
    //         $pageName = "Blog Page";
    //     } elseif ($postId) {
    //         $pageName = "Post Page";
    //     } else {
    //         $pageName = "Other Page"; // Baqi pages ke liye
    //     }

    //     // Duplicate check: Sirf ek entry per refresh allow karni hai
    //     $existingVisitor = Visitor::where('visitor_ip', $visitorIp)
    //         ->where('post_id', $postId)
    //         ->where('page_name', $pageName)
    //         ->where('created_at', '>=', now()->subSeconds(5)) // 5 sec me ek hi entry
    //         ->first();

    //     if (!$existingVisitor) {
    //         Visitor::create([
    //             'post_id' => $postId,
    //             'visitor_ip' => $visitorIp,
    //             'page_name' => $pageName, // Page Name Save Karein
    //         ]);
    //     }

    //     return $next($request);
    // }
    // public function handle(Request $request, Closure $next)
    // {
    //     $postId = $request->route('id'); // Get route id to route
    //     $visitorIp = $request->ip();
    //     $slug = $request->route('slug'); // get slug

    //     // set page name
    //     if ($request->is('/')) {
    //         $pageName = "Home Page";
    //     } elseif ($request->is('about')) {
    //         $pageName = "About Page";
    //     } elseif ($request->is('contact')) {
    //         $pageName = "Contact Page";
    //     } elseif ($request->is('blog')) {
    //         $pageName = "Blog Page";
    //     } elseif ($slug) {
    //         $pageName = "Tag Page";
    //     } elseif ($postId) {
    //         $pageName = "Post Page";
    //     } else {
    //         $pageName = "Other Page";
    //     }


    //     $existingVisitor = Visitor::where('visitor_ip', $visitorIp)
    //         ->where('post_id', $postId)
    //         ->where('page_name', $pageName)
    //         ->where('created_at', '>=', now()->subSeconds(5))
    //         ->first();

    //     if (!$existingVisitor) {
    //         Visitor::create([
    //             'post_id' => $postId,
    //             'visitor_ip' => $visitorIp,
    //             'page_name' => $pageName,
    //         ]);
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next)
{
    $postId = $request->route('id'); // Get Post ID from route
    $visitorIp = $request->ip();
    $slug = $request->route('slug'); // Get Slug (for Tag Page)

    
    if ($postId) {
        $pageName = "Post Page";
    } elseif ($slug) {
        $pageName = "Tag Page";
    } elseif ($request->is('/')) {
        $pageName = "Home Page";
    } elseif ($request->is('about')) {
        $pageName = "About Page";
    } elseif ($request->is('contact')) {
        $pageName = "Contact Page";
    } elseif ($request->is('blog')) {
        $pageName = "Blog Page";
    } else {
        $pageName = "Other Page";
    }

    $existingVisitor = Visitor::where('visitor_ip', $visitorIp)
        ->where('post_id', $postId)
        ->where('page_name', $pageName)
        ->where('created_at', '>=', now()->subSeconds(5))
        ->first();

    if (!$existingVisitor) {
        Visitor::create([
            'post_id' => $postId,
            'visitor_ip' => $visitorIp,
            'page_name' => $pageName,
        ]);
    }

    return $next($request);
}
















}
