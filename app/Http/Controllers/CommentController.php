<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
//     public function __construct()
// {
//     $this->middleware('auth');
// }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'post_id' => 'required|exists:posts,id',
    //         'comment' => 'required|string',
    //     ]);

    //     Comment::create([
    //         'post_id' => $request->post_id,
    //         'comment' => $request->comment,
    //         'is_approved' => 0, // Default false
    //         'published_at' => null,
    //     ]);

    //     return back()->with('success', 'Comment submitted successfully and awaiting approval.');
    // }
public function store(Request $request)
{
    // Check if user is logged in
    if (!auth()->check()) {
        return back()->with('login_error', 'You must log in first to comment.');
    }

    // Check if user is super_admin (1) or author (2)
    if (!in_array(auth()->user()->role_id, [1, 2])) {
        return back()->with('error', 'Only super admins and authors can comment.');
    }

    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'comment' => 'required|string',
    ]);

    Comment::create([
        'post_id' => $request->post_id,
        'comment' => $request->comment,
        'is_approved' => 0, // Default as unapproved
        'published_at' => null, // Initially NULL
    ]);

    return back()->with('success', 'Your comment has been submitted and is awaiting approval.');
}

}
