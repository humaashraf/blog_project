<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Visitor;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PostService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;


class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        //
        $this->middleware(['auth' ,'role:admin,manager']); // Only admin can access
    }


    public function index(Request $request)
    {

        if ($request->ajax()) {
            $role = auth()->user()->roles()->first();

            // $posts = $this->postService->getPostsForUser();
            // Filter only approved and published posts
            $posts = $this->postService->getPostsForUser()->where('is_approved', 1)->whereNotNull('published_at');
            // $posts = collect([]);
            return DataTables::of($posts)
                ->addColumn('category', fn($row) => $row->category->name ?? 'No Category')
                ->addColumn('tags', fn($row) => $row->tags->pluck('name')->implode(', ') ?? 'No Tags')
                ->addColumn('author', fn($row) => $row->author->name ?? 'No Author') // Show author name
                ->addColumn('image', fn($row) => $row->image ? '<img src="' . asset('uploads/' . $row->image) . '" width="50">' : 'No Image')
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 0) {
                        return '<span class="badge bg-warning">Processing</span>';
                    } elseif ($row->is_approved == 1) {
                        return '<span class="badge bg-success">Approved</span>';
                    } elseif ($row->is_approved == 3) {
                        return '<span class="badge bg-danger">Denied</span>';
                    }
                    return '<span class="badge bg-secondary">Unknown</span>';
                })
        //         ->addColumn('action', fn($row)  => '<a href="javascript:void(0)" class="btn-sm btn btn-info viewButton" data-slug="' . Str::slug($row->title) . '" data-id="' . $row->id . '">View</a>
        //                 <a href="javascript:void(0)" class="btn-sm btn btn-warning editButton" data-id="' . $row->id . '">Edit</a>
        //                 <a href="javascript:void(0)" class="btn-sm btn btn-danger delButton" data-id="' . $row->id . '">Delete</a>')
        //         ->rawColumns(['image', 'approval_status', 'action'])
        //         ->make(true);
        // }

        // $categories = Category::all();
        // $tags = Tag::all();
        // return view('backend.admin.posts.create', compact('categories', 'tags'));
        ->addColumn('action', function ($row) use ($role) {
            $buttons = '';

            // Check if user has permission to view, edit, or delete posts
                            // View button
                            // $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-info viewButton" data-slug="' . Str::slug($row->title) . '" data-id="' . $row->id . '">View</a> ';

            if ($role->hasPermissionTo('backend.admin.post.show')) {
                $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-info viewButton" data-slug="' . Str::slug($row->title) . '" data-id="' . $row->id . '">View</a> ';
            }

            if ($role->hasPermissionTo('backend.admin.posts.edit')) {
                $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-warning editButton" data-id="' . $row->id . '">Edit</a> ';
            }

            if ($role->hasPermissionTo('backend.admin.posts.delete')) {
                $buttons .= '<a href="javascript:void(0)" class="btn-sm btn btn-danger delButton" data-id="' . $row->id . '">Delete</a>';
            }

            return $buttons;
        })
        ->rawColumns(['image', 'approval_status', 'action'])  // Render raw HTML for image, approval status, and action buttons
        ->make(true);
}

// Get categories and tags for the create post view
$categories = Category::all();
$tags = Tag::all();

// Return the create view with categories and tags
return view('backend.admin.posts.create', compact('categories', 'tags'));
    }

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

    //     public function store(Request $request)
    // {
    //     try {
    //         Log::info('Storing post:', $request->all());

    //         $post = $request->post_id ? Post::find($request->post_id) : null;

    //         if ($request->post_id && !$post) {
    //             return response()->json([
    //                 'error' => 'Post not found!'
    //             ], 404);
    //         }

    //         // // Post save karne se pehle is_approved aur published_by set kar dein
    //         // $request->merge([
    //         //     'is_approved' => 1, // Approve by default
    //         //     'published_by' => auth()->user()->id // Current admin user ID
    //         // ]);

    //         $post = $this->postService->savePost($request, $post);

    //         return response()->json([
    //             'success' => $post->wasRecentlyCreated ? 'Post created successfully!' : 'Post updated successfully!',
    //             'post' => $post
    //         ], $post->wasRecentlyCreated ? 201 : 200);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         Log::warning('Validation Error:', $e->errors());
    //         return response()->json([
    //             'errors' => $e->errors()
    //         ], 422);

    //     } catch (\Exception $e) {
    //         Log::error('Post store error:', [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return response()->json([
    //             'error' => 'Something went wrong. Please try again later!',
    //         ], 500);
    //     }
    // }


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
    //     public function show($id)
    // {
    //     $post = Post::findOrFail($id);
    //     return view('frontend.post-detail', compact('post'));
    // }

    // public function show($slug, $id, $request)
    // {
    //     $post = Post::findOrFail($id);
    //         // Visitor data save karo
    //         Visitor::create([
    //             'post_id' => $post->id,
    //             'visitor_ip' => $request->ip(),
    //         ]);
    //     return view('frontend.post-detail', compact('post'));
    // }


    public function show($slug, $id, Request $request)  // Request ko dependency injection se lo
    {
        $post = Post::findOrFail($id);

        // Visitor data save karo
        Visitor::create([
            'post_id' => $post->id,
            'visitor_ip' => $request->ip(),
        ]);

        return view('frontend.post-detail', compact('post'));
    }

    // public function show_admin($id)
    // {
    //     $post = Post::findOrFail($id);
    //     return view('frontend.post-detail', compact('post'));
    // }

}
