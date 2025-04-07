<?php namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostService
{
    // public function getPostsForUser()
    // {
    //     $user = Auth::user();

    //     if (in_array($user->role->name, ['admin', 'admin'])) {
    //         return Post::with('category', 'tags')->get();
    //     } else {
    //         return Post::with('category', 'tags')->where('author_id', Auth::id())->get();
    //     }
    // }
//     public function getPostsForUser()
// {
//     $user = Auth::user();

//     if (!$user) {
//         return collect();
//     }

//     $query = Post::with('category', 'tags', 'author');

//     if (!$user->hasRole('admin')) {
//         $query->where('author_id', $user->id);
//     }

//     return $query;
// }
public function getPostsForUser()
{
    $user = Auth::user();

    if (!$user) {
        return collect();
    }

    $query = Post::with(['category', 'tags', 'author']);

    if ($user->type !== 'admin') {
        $query->where('author_id', $user->id);
    }

    return $query->get();
}


    // public function getPostsForUser()
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $query = Post::with('category', 'tags');

    //     if (!$user->hasRole(['admin'])) {
    //         $query->where('author_id', Auth::id());
    //     }

    //     $posts = $query->get();

    //     return response()->json(['data' => $posts]);
    // }


    // public function getPostsForUser()
    // {
    //     $user = Auth::user();

    //     $query = Post::with(['category', 'tags']);

    //     if (!in_array($user->role->name ?? '', ['admin', 'admin'])) {
    //         $query->where('author_id', $user->id);
    //     }

    //     // Pehle `get()` use karna hai
    //     $posts = $query->get();

    //     return response()->json(['data' => $posts]);
    // }






    public function savePost($request, $post = null)
{
    $rules = [
        'title' => 'required|min:2|max:30',
        'description' => 'required',
        'category_id' => 'required|exists:categories,id',
    ];

    if (!$post) {
        $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
    } elseif ($request->hasFile('image')) {
        $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
    }

    $validatedData = $request->validate($rules);
    Log::info('Post request data:', $request->all());

    // Keep the existing image name if the post already exists
    $imageName = $post ? $post->image : null;

    if ($request->hasFile('image')) {
        if ($imageName && file_exists(public_path('uploads/' . $imageName))) {
            unlink(public_path('uploads/' . $imageName));
        }
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);
    }

    // Prepare data to be saved
    $data = [
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'image' => $imageName,
    ];

    // Don't change the author_id if the post already exists (i.e., during update)
    if (!$post) {
        $data['author_id'] = Auth::id(); // Set author_id only during creation
    } else {
        $data['author_id'] = $post->author_id; // Keep the original author_id during update
    }
    // if (Auth::user()->role->name === 'super_admin') {
    //     $data['is_approved'] = 1;
    //     $data['published_by'] = now(); // Current DateTime
    // }

    if ($post) {
        $post->update($data);
    } else {
        $post = Post::create($data);
    }

    if ($request->has('tag_ids') && is_array($request->tag_ids)) {
        $post->tags()->sync($request->tag_ids);
    }

    return $post;
}


    public function deletePost($id)
    {
        $post = Post::find($id);
        if ($post) {
            if ($post->image && file_exists(public_path('uploads/' . $post->image))) {
                unlink(public_path('uploads/' . $post->image));
            }
            $post->delete();
            return true;
        }
        return false;
    }
}
