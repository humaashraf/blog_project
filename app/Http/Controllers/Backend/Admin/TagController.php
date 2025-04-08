<?php

// namespace App\Http\Controllers;

namespace App\Http\Controllers\Backend\Admin;
use App\Http\Controllers\Controller;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class TagController extends Controller
{
//     public function index(Request $request)
// {
//     if ($request->ajax()) {
//         $tags = Tag::with('posts')->select('id', 'name');

//         return DataTables::of($tags)
//             ->addColumn('posts', function ($row) {
//                 return $row->posts->pluck('title')->implode(', ');
//             })
//             ->addColumn('action', function ($row) {
//                 return '<a href="javascript:void(0)" class="btn-sm btn btn-info editButton" data-id="'.$row->id.'">Edit</a>
//                         <a href="javascript:void(0)" class="btn-sm btn btn-danger delButton" data-id="'.$row->id.'">Delete</a>';
//             })
//             ->rawColumns(['action'])
//             ->make(true);
//     }

//     $posts = Post::all(); // Fetch posts
//     return view('backend.admin.tags.create', compact('posts'));
// }

public function index(Request $request)
{
    if ($request->ajax()) {
        $role = auth()->user()->roles()->first();  // Get the user's first role

        $tags = Tag::with('posts')->select('id', 'name');

        return DataTables::of($tags)
            ->addColumn('posts', function ($row) {
                return $row->posts->pluck('title')->implode(', ');
            })
            ->addColumn('action', function ($row) use ($role) {
                $buttons = '';

                // Check if the user is authenticated and has the right permissions
                if (auth()->check()) {

                    // Check if the user has permission to edit the tag
                    if ($role->hasPermissionTo('backend.admin.tags.edit')) {
                        $buttons .= '<a href="' . route('backend.admin.tags.edit', $row->id) . '" class="btn-sm btn btn-info editButton">Edit</a> ';
                    }

                    // Check if the user has permission to delete the tag
                    if ($role->hasPermissionTo('backend.admin.tags.delete')) {
                        $buttons .= '<a href="' . route('backend.admin.tags.delete', $row->id) . '" class="btn-sm btn btn-danger delButton">Delete</a>';
                    }
                }

                return $buttons;  // Return the action buttons
            })
            ->rawColumns(['action'])  // Make the action column raw to support HTML
            ->make(true);
    }

    $posts = Post::all(); // Fetch posts
    return view('backend.admin.tags.create', compact('posts'));
}



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:30',
            'post_ids' => 'array'  // Validate post IDs as an array
        ]);

        if ($request->tag_id != null) {
            $tag = Tag::find($request->tag_id);
            if (!$tag) {
                abort(404);
            } else {
                $tag->update(['name' => $request->name]);

                // Sync many-to-many relationship
                $tag->posts()->sync($request->post_ids ?? []);

                return response()->json(['success' => 'Tag Updated successfully!'], 201);
            }
        } else {
            $tag = Tag::create(['name' => $request->name]);

            // Attach posts to the newly created tag
            $tag->posts()->attach($request->post_ids ?? []);

            return response()->json(['success' => 'Tag Saved successfully!'], 201);
        }
    }

    public function edit($id)
    {
        $tag = Tag::with('posts')->find($id);
        if (!$tag) {
            abort(404);
        }
        return response()->json($tag);
    }

    public function delete($id)
    {
        $tag = Tag::find($id);
        if (!$tag) {
            abort(404);
        }

        // Detach all associated posts before deleting the tag
        $tag->posts()->detach();
        $tag->delete();

        return response()->json(['success' => 'Tag Deleted Successfully!'], 201);
    }

public function showPosts($slug)
{
    $tag = Tag::all()->filter(function ($tag) use ($slug) {
        return Str::slug($tag->name) === $slug;
    })->first();

    if (!$tag) {
        abort(404);
    }

    $posts = $tag->posts()
        ->whereNotNull('published_at')
        ->latest()
        ->get();

    return view('frontend.tag_posts', compact('tag', 'posts'));
}


}
