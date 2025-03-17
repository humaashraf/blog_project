<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\PostReason;
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    // public function markAsRead(Request $request)
    // {
    //     $user = Auth::user();

    //     // Post ki details nikalain
    //     $post = Post::find($request->post_id);

    //     if (!$post) {
    //         return response()->json(['success' => false, 'message' => 'Post not found']);
    //     }

    //     // Unread notifications filter karain jo iss post_id se match karti hain
    //     $notifications = $user->unreadNotifications->where('data.post_id', $request->post_id);

    //     foreach ($notifications as $notification) {
    //         $notification->markAsRead();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'title' => $post->title, // Post ka title
    //         'reason' => $post->reason // Post ka reason (is column ka hona zaroori hai)
    //     ]);
    // }


        // public function markAsRead(Request $request)
        // {
        //     $user = Auth::user();

        //     // Pehle Post Ki Details Nikalain
        //     $post = Post::find($request->post_id);

        //     // Agar Post Exist Nahi Karti
        //     if (!$post) {
        //         return response()->json(['success' => false, 'message' => 'Post not found']);
        //     }

        //     // PostReason se Reason Retrieve Karen
        //     $postReason = PostReason::where('post_id', $request->post_id)->first();

        //     // Unread notifications filter karain jo iss post_id se match karti hain
        //     $notifications = $user->unreadNotifications->where('data.post_id', $request->post_id);

        //     foreach ($notifications as $notification) {
        //         $notification->markAsRead();
        //     }

        //     return response()->json([
        //         'success' => true,
        //         'title' => $post->title, // Post ka Title
        //         'reason' => $postReason ? $postReason->reason : 'No reason provided' // PostReason ka Reason
        //     ]);
        // }




    public function markAsRead(Request $request)
    {
        $user = Auth::user();

        // Find the notification that matches post_id
        $notifications = $user->unreadNotifications->where('data.post_id', $request->post_id);

        foreach ($notifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}



