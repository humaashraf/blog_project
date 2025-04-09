<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Notifications\PostRejectedNotification;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\PostApprovedNotification;
// use App\Models\PostReason;


class PendingPostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware(['auth', 'role:admin']); // Only super_admin can access
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $role = auth()->user()->roles()->first();
            // $posts = $this->postService->getPostsForUser();
                    // Filter only approved and published posts
        $posts = $this->postService->getPostsForUser()->where('is_approved', 0)->whereNull('published_at');

            return DataTables::of($posts)
                ->addColumn('category', fn($row) => $row->category->name ?? 'No Category')
                ->addColumn('tags', fn($row) => $row->tags->pluck('name')->implode(', ') ?? 'No Tags')
                ->addColumn('author', fn($row) => $row->author->name ?? 'No Author') // Show author name
                ->addColumn('image', fn($row) => $row->image ? '<img src="' . asset('uploads/' . $row->image) . '" width="50">' : 'No Image')
        //         ->addColumn('action', fn($row) => '<a href="javascript:void(0)" class="btn-sm btn btn-danger denyButton" data-id="' . $row->id . '">Deny</a>
        //         <a href="javascript:void(0)" class="btn-sm btn btn-success approveButton" data-id="' . $row->id . '">Approve</a>
        //         <button class="btn btn-danger btn-sm deleteBtn" data-id="'.$row->id.'">Delete</button>')
        //         ->rawColumns(['image', 'action'])
        //         ->make(true);
        // }
        ->addColumn('action', function ($row) use ($role) {
            $buttons = '';

            if (auth()->check()) {
                if ($role->hasPermissionTo('backend.admin.pendingposts.deny')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-danger denyButton" data-id="' . $row->id . '">Deny</a> ';
                }

                if ($role->hasPermissionTo('backend.admin.pendingposts.approve')) {
                    $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-success approveButton" data-id="' . $row->id . '">Approve</a> ';
                }

                if ($role->hasPermissionTo('backend.admin.pendingposts.delete')) {
                    $buttons .= '<button class="btn btn-danger btn-sm deleteBtn" data-id="'.$row->id.'">Delete</button>';
                }
            }

            return $buttons;
        })
        ->rawColumns(['action', 'image'])
        ->make(true);
}

        $categories = Category::all();
        $tags = Tag::all();
        return view('backend.admin.pendingposts.create', compact('categories', 'tags'));
    }


public function denyPost(Request $request, $id)
{
    $post = Post::find($id);

    if (!$post) {
        return response()->json(['error' => 'Post not found!'], 404);
    }


    // DB::table('post_reason')->insert([
    //     'post_id' => $id,
    //     'reason' => $request->reason,
    //     'created_at' => now(),
    //     'updated_at' => now(),
    // ]);


    $post->is_approved = 3;
    $post->save();

    $post->reason()->create(['reason' => $request->reason]);

    $post->author->notify(new PostRejectedNotification($post, 'denied', $request->reason));

    return response()->json(['success' => 'Post has been denied successfully!']);
}



    public function approve($id)
{
    $post = Post::find($id);

    if (!$post) {
        return response()->json(['error' => 'Post not found!'], 404);
    }

    $post->is_approved = 1;
    $post->published_at = Carbon::now();
    $post->save();

    return response()->json(['success' => 'Post approved successfully!']);
}

// public function approve($id)
// {
//     $post = Post::find($id);

//     if (!$post) {
//         return response()->json(['error' => 'Post not found'], 404);
//     }

//     $post->is_approved = 1; // Approve post
//     $post->published_at = Carbon::now();
//     $post->save();

//     // Notification send karein user ko
//     $post->user->notify(new PostApprovedNotification($post));

//     return response()->json(['message' => 'Post approved successfully']);
// }




// public function approve($id)
// {
//     $post = Post::find($id);

//     if (!$post) {
//         return response()->json(['error' => 'Post not found'], 404);
//     }

//     $post->is_approved = 1; // Approve post
//     $post->published_at = now();
//     $post->save();

//     // Debugging ke liye check karein:
//     dd($post->id, $post->title);

//     // Notification send karein user ko
//     $post->user->notify(new PostApprovedNotification($post));

//     return response()->json(['message' => 'Post approved successfully']);
// }



    public function store(Request $request)
    {
        try {
            Log::info('Storing post:', $request->all());

            $post = $request->post_id ? Post::find($request->post_id) : null;

            if ($request->post_id && !$post) {
                return response()->json([
                    'error' => 'Post not found!'
                ], 404);
            }

            // $post = $this->postService->savePost($request, $post);
            // Save post using service class
        $post = $this->postService->savePost($request, $post);

        // Only set is_approved and published_at here
        $post->is_approved = 1;
        $post->published_at = Carbon::now();
        $post->save(); // Save the changes

            return response()->json([
                'success' => $post->wasRecentlyCreated ? 'Post created successfully!' : 'Post updated successfully!',
                'post' => $post
            ], $post->wasRecentlyCreated ? 201 : 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation Error:', $e->errors());
            return response()->json([
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Post store error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Something went wrong. Please try again later!',
            ], 500);
        }
    }


    public function edit($id)
    {
        $post = Post::find($id);
        return $post ? response()->json($post) : abort(404);
    }

    public function delete($id)
    {
        return $this->postService->deletePost($id) ?
            response()->json(['success' => 'Post Deleted Successfully!'], 200) :
            abort(404);
    }
}
