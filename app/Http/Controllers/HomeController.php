<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Newsletter;
use App\Models\Contact;
use App\Models\ContactSlider;
use App\Models\About;
use App\Models\Setting;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Visitor;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(\App\Http\Middleware\LogVisitor::class); // Middleware apply
    // }
    // public function __construct()
    // {
    //     $this->middleware('log.visitor'); // Middleware ka sahi tareeqa
    // }

public function home(Request $request)
{
    // Visitor IP
    // Visitor::create([
    //     'post_id' => null,
    //     'visitor_ip' => $request->ip(),
    // ]);

    // Home page visitor ip
    // Visitor::create([
    //     'post_id' => null,
    //     'visitor_ip' => $request->ip(),
    // ]);



    $sliders = Slider::orderBy('sort_by', 'asc')->get(); // Pehla slider
    $contactSliders = ContactSlider::orderBy('sort_by', 'asc')->get(); // Dusra slider
    $about = About::first();
    $settings = Setting::first();

    // separately fetch every post
    $firstPost = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->first();

    $secondLastPost = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->skip(1)
        ->first();

    $thirdLastPost = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->skip(2)
        ->first();

    $fourthLastPost = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->skip(3)
        ->first();

    // first 4 posts IDs
    $excludedPostIds = collect([$firstPost, $secondLastPost, $thirdLastPost, $fourthLastPost])
        ->filter() // Null values
        ->pluck('id');

    // posts paginate
    $remainingPosts = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->whereNotIn('id', $excludedPostIds) // exclude first 4 posts
        ->orderBy('published_at', 'desc')
        ->paginate(6); // Pagination enable

        // $tags = Tag::whereHas('posts') // Sirf wo tags jo kisi post me use hue hain
        // ->withCount('posts') // Har tag ke sath posts count bhi la raha hai
        // ->orderBy('posts_count', 'desc') // Jitni posts me use hua hai us order me
        // ->latest() // Sabse latest tags
        // ->take(10) // Sirf 10 tags
        // ->get();
        $tags = Tag::whereHas('posts', function ($query) {
            $query->whereNotNull('published_at'); // Sirf published posts count hongi
        })
        ->withCount([
            'posts' => function ($query) {
                $query->whereNotNull('published_at'); // Published posts ka hi count
            }
        ])
        ->orderBy('posts_count', 'desc') // Jitni posts me use hua hai us order me
        ->latest() // Sabse latest tags
        ->take(10) // Sirf 10 tags
        ->get();


    return view('frontend.home', compact(
        'sliders', 'contactSliders', 'about', 'settings',
        'fourthLastPost', 'thirdLastPost', 'secondLastPost', 'firstPost', 'remainingPosts', 'tags'
    ));
}

// public function showTags()
// {
//     $tags = Tag::withCount('posts') // Posts count ke sath fetch karega
//                 ->orderBy('created_at', 'desc')
//                 ->limit(10)
//                 ->get();

//     return view('home', compact('tags'));
// }




    public function about()
    {

        $about = About::first(); // About data fetch
        $settings = Setting::first();
        // $latestPost = Post::latest()->first(); // Latest post fetch
        $firstPost = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->first();
        return view('frontend.about', compact('about', 'settings', 'firstPost'));
    }

    public function contact()
    {
        $contactSliders = ContactSlider::orderBy('sort_by', 'asc')->get(); // Contact Sliders
        $settings = Setting::first();
        return view('frontend.contact', compact('contactSliders', 'settings'));
    }



    // public function blog()
    // {
    //     $settings = Setting::first();
    //     return view('frontend.blog', compact('settings'));
    // }
//     public function blog()
// {
//     $settings = Setting::first();

//     // Fetch recent blog posts
//     $recentPosts = Post::whereNotNull('published_at')
//         ->where('is_approved', 1)
//         ->orderBy('published_at', 'desc')
//         ->take(5) // Adjust the number as needed
//         ->get();

//     return view('frontend.blog', compact('settings', 'recentPosts'));
// }
public function blog()
{
    $settings = Setting::first();

    // Fetch recent blog posts
    $recentPosts = Post::whereNotNull('published_at')
        ->where('is_approved', 1)
        ->orderBy('published_at', 'desc')
        ->take(5)
        ->get()
        ->map(function ($post) {
            $post->slug = Str::slug($post->title); // Generate slug
            return $post;
        });

    return view('frontend.blog', compact('settings', 'recentPosts'));
}


    public function show($slug, $id, Request $request)  // Request ko dependency injection se lo
    {
        $post = Post::findOrFail($id);

        // Visitor data save karo
        // Visitor::create([
        //     'post_id' => $post->id,
        //     'visitor_ip' => $request->ip(),
        // ]);

        return view('frontend.post-detail', compact('post'));
    }

    // public function show($id, $request)
    // {
    //     $post = Post::findOrFail($id);
    //     // Visitor data save karo
    // Visitor::create([
    //     'post_id' => $post->id,
    //     'visitor_ip' => $request->ip(),
    // ]);
    //     return view('frontend.post-detail', compact('post'));
    // }
    public function subscribeNewsletter(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:newsletters,email',
    ]);

    // Data insert
    Newsletter::create([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return back()->with('success', 'Subscription successful!');
}

public function submitContactForm(Request $request)
{
    // Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|digits:11', // 11-digit phone number required
        'message' => 'required|string|min:5',
    ]);

    // Data Insert
    Contact::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'message' => $request->message,
    ]);

    return back()->with('success', 'Your message has been sent successfully!');
}




public function settingsPage()
{
    $settings = Setting::first();
    return view('frontend.layouts.header', compact('settings'));
}



}
