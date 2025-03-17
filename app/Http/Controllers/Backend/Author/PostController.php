<?php

namespace App\Http\Controllers\Backend\Author;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use App\Models\PostReason;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
// use App\Notifications\PostDeniedNotification;
use App\Notifications\PostRejectedNotification;
use Illuminate\Support\Facades\Notification;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = $this->postService->getPostsForUser();

            return DataTables::of($posts)
                ->addColumn('category', fn($row) => $row->category->name ?? 'No Category')
                ->addColumn('tags', fn($row) => $row->tags->pluck('name')->implode(', ') ?? 'No Tags')
                ->addColumn('image', fn($row) => $row->image ? '<img src="' . asset('uploads/' . $row->image) . '" width="50">' : 'No Image')
                ->addColumn('approval_status', function ($row) {
                    if ($row->is_approved == 0) {
                        return '<span class="badge bg-secondary">Processing...</span>';
                    } elseif ($row->is_approved == 1) {
                        return '<span class="badge bg-success">Approved</span>';
                    } elseif ($row->is_approved == 3) {
                        return '<span class="badge bg-danger">Denied</span>
                                <button class="btn btn-sm btn-warning viewReasonButton mt-1" data-id="' . $row->id . '">Reason</button>';
                    }
                    return '<span class="badge bg-secondary">Unknown</span>';
                })
                ->addColumn('action', fn($row) => '<a href="javascript:void(0)" class="btn-sm btn btn-info viewButton" data-slug="' . Str::slug($row->title) . '" data-id="' . $row->id . '">View</a>
                        <a href="javascript:void(0)" class="btn-sm btn btn-warning editButton" data-id="' . $row->id . '">Edit</a>
                        <a href="javascript:void(0)" class="btn-sm btn btn-danger delButton" data-id="' . $row->id . '">Delete</a>')
                ->rawColumns(['image', 'approval_status', 'action'])
                ->make(true);
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('backend.author.posts.create', compact('categories', 'tags'));
    }

// public function getReason(Request $request)
//     {
//         $post_id = $request->query('post_id'); // GET parameter se post_id lein

//         // PostReason table se data fetch karein
//         $postReason = PostReason::where('post_id', $post_id)->latest()->->first();

//         if ($postReason) {
//             return response()->json([
//                 'post_id' => $postReason->post_id,
//                 'reason' => $postReason->reason // reason column ka value
//             ]);
//         } else {
//             return response()->json(['message' => 'No reason found'], 404);
//         }
//     }
public function getReason(Request $request)
{
    $postId = $request->post_id;

    // Latest reason ko fetch karein
    $reason = PostReason::where('post_id', $postId)->latest()->first();

    return response()->json([
        'reason' => $reason ? $reason->reason : 'No reason found.'
    ]);
}





public function pendingPostsCount()
{
    $count = \App\Models\Post::where('is_approved', 0)->count();
    return response()->json(['count' => $count]);
}




    // public function store(Request $request)
    // {
    //     try {
    //         Log::info('Storing post:', $request->all());

    //         // Retrieve existing post if post_id is provided
    //         $post = $request->post_id ? Post::find($request->post_id) : null;

    //         if ($request->post_id && !$post) {
    //             return response()->json([
    //                 'error' => 'Post not found!'
    //             ], 404);
    //         }

    //         // Set default approval status
    //         $request->merge(['is_approved' => 0]);

    //         // Use PostService to save or update the post
    //         $post = $this->postService->savePost($request, $post);

    //         return response()->json([
    //             'success' => $post->wasRecentlyCreated ? 'Post created successfully and is under processing!' : 'Post updated successfully!',
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

    public function store(Request $request)
{
    try {
        Log::info('Storing post:', $request->all());

        // Retrieve existing post if post_id is provided
        $post = $request->post_id ? Post::find($request->post_id) : null;

        if ($request->post_id && !$post) {
            return response()->json([
                'error' => 'Post not found!'
            ], 404);
        }

        // Ensure `is_approved` is always 0
        $request->merge(['is_approved' => 0]);

        // Use PostService to save or update the post
        $post = $this->postService->savePost($request, $post);

        // Forcefully update is_approved to 0 after save (in case it's ignored)
        $post->is_approved = 0;
        $post->save();

        Log::info('Post saved with is_approved = 0', ['post_id' => $post->id]);

        return response()->json([
            'success' => $post->wasRecentlyCreated ? 'Post created successfully and is under processing!' : 'Post updated successfully!',
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
//     public function updatePostStatus(Request $request, $id)
// {
//     $post = Post::findOrFail($id);
//     $post->is_approved = $request->status;
//     $post->save();

//     if ($post->is_approved == 3) { // If post is denied
//         // Save the reason in post_reason table
//         PostReason::create([
//             'post_id' => $post->id,
//             'reason' => $request->reason,
//         ]);

//         // Send notification to author
//         $author = $post->author;
//         $author->notify(new PostDeniedNotification($post, $request->reason));
//     }

//     return response()->json(['message' => 'Post status updated successfully']);
// }


public function updateApproval(Request $request, $id)
{
    $post = Post::findOrFail($id);

    // Update is_approved status
    $post->is_approved = $request->is_approved;
    $post->save();

    // If post is rejected (is_approved = 3), save the reason and notify user
    if ($post->is_approved == 3) {
        $reason = $request->reason;

        // Store reason in post_reason table
        PostReason::updateOrCreate(
            ['post_id' => $post->id],
            ['reason' => $reason]
        );

        // Send notification to the author
        $post->author->notify(new PostRejectedNotification($post, $reason));
    }

    return redirect()->back()->with('success', 'Post approval updated.');
}
// public function show($id)
// {
//     $post = Post::findOrFail($id);
//     return view('frontend.post-detail', compact('post'));
// }
public function show($slug, $id)
{
    $post = Post::findOrFail($id);
    return view('frontend.post-detail', compact('post'));
}


}
