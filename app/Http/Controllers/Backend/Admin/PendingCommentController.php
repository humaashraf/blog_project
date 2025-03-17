<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PendingCommentController extends Controller
{
    public function index(Request $request)
{
    if ($request->ajax()) {
        $comments = Comment::with('post')->select('comments.*')->where('is_approved', 0)->whereNull('published_at');
        return DataTables::of($comments)
            ->addColumn('post_title', function ($comment) {
                return $comment->post ? $comment->post->title : 'N/A';
            })
            ->addColumn('action', function ($comment) {
                return '<a href="javascript:void(0)" class="btn-sm btn btn-success approveButton" data-id="' . $comment->id . '">Approve</a>
                <button class="btn-sm btn btn-danger deleteBtn" data-id="'.$comment->id.'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('backend.admin.comments.index');
}

public function approve($id)
{
    $comment = Comment::find($id);

    if (!$comment) {
        return response()->json(['error' => 'Post not found!'], 404);
    }

    $comment->is_approved = 1;
    $comment->published_at = Carbon::now();
    $comment->save();

    return response()->json(['success' => 'Comment approved successfully!']);
}

    public function destroy($id)
    {
        Comment::findOrFail($id)->delete();
        return response()->json(['success' => 'Comment deleted successfully.']);
    }
}
