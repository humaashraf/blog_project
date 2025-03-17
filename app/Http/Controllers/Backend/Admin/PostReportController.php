<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PostReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('is_approved', 1) // Approved posts only
        ->whereNotNull('published_at');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('author')) {
            $query->where('author_id', $request->author);
        }

        $posts = $query->get();

        return view('backend.admin.posts.post_report', compact('posts'));
    }

    public function exportPDF(Request $request)
{
    $query = Post::query();

    // Apply filters
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    if ($request->has('author')) {
        $query->where('author_id', $request->author);
    }

    $posts = $query->get(); // Filtered posts

    $pdf = PDF::loadView('backend.admin.posts.post_pdf', compact('posts'));

    return $pdf->download('posts.pdf');
}

}

