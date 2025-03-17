<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostReason;
use App\Models\Post;
use App\Notifications\PostRejectedNotification;

class PostReasonController extends Controller
{
    public function denyPost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'denied';
        $post->save();

        // Store reason in post_reason table
        PostReason::create([
            'post_id' => $id,
            'reason' => $request->reason,
        ]);

        return response()->json(['success' => 'Post has been denied successfully!']);
    }
    public function rejectPost($postId)
{
    $post = Post::find($postId);
    $reason = PostReason::where('post_id', $postId)->first();

    if (!$post || !$reason) {
        return back()->with('error', 'Post or reason not found');
    }

    // Send notification to post author
    $user = $post->user; // Assuming 'user' relationship exists in Post model
    $user->notify(new PostRejectedNotification($post, $reason->reason));

    return back()->with('success', 'Post rejected and notification sent');
}

}
