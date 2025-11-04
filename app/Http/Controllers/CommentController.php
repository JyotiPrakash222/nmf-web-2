<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ViewerComment;
use App\Models\CommentLike;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::guard('viewer')->check()) {
            return response()->json(['message' => 'कृपया टिप्पणी करने के लिए लॉग इन करें।'], 401);
        }

        $validated = $request->validate([
            'body' => 'required|min:3|max:1000',
            'model_id' => 'required|integer|exists:blogs,id',
        ]);

        $comment = ViewerComment::create([
            'body' => $validated['body'],
            'commentable_id' => $validated['model_id'],
            'commentable_type' => \App\Models\Blog::class,
            'viewer_id' => Auth::guard('viewer')->id(),
        ]);

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'viewer_name' => $comment->viewer->name,
                'viewer_initial' => substr($comment->viewer->name, 0, 1)
            ]
        ]);
    }

    public function reply(Request $request)
    {
        if (!Auth::guard('viewer')->check()) {
            return response()->json(['message' => 'कृपया जवाब देने के लिए लॉग इन करें।'], 401);
        }

        $validated = $request->validate([
            'replyBody' => 'required|min:3|max:1000',
            'parent_id' => 'required|integer|exists:viewercomments,id',
            'model_id' => 'required|integer|exists:blogs,id',
        ]);

        $comment = ViewerComment::create([
            'body' => $validated['replyBody'],
            'commentable_id' => $validated['model_id'],
            'commentable_type' => \App\Models\Blog::class,
            'viewer_id' => Auth::guard('viewer')->id(),
            'parent_id' => $validated['parent_id'],
        ]);

        return response()->json([
            'comment' => [
                'body' => $comment->body,
                'viewer_name' => $comment->viewer->name,
                'viewer_initial' => strtoupper(substr($comment->viewer->name, 0, 1)),
                'likes_count' => $comment->likes_count,
                'id' => $comment->id
            ]
        ]);

    }

    public function toggleLike($commentId)
    {
        if (!Auth::guard('viewer')->check()) {
            return response()->json(['message' => 'कृपया लाइक करने के लिए लॉग इन करें।'], 401);
        }

        $viewerId = Auth::guard('viewer')->id();
        $existingLike = CommentLike::where('comment_id', $commentId)
            ->where('viewer_id', $viewerId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $likesCount = CommentLike::where('comment_id', $commentId)->count();

            return response()->json([
                'liked' => false,
                'likes_count' => $likesCount
            ]);
        } else {
            CommentLike::create([
                'comment_id' => $commentId,
                'viewer_id' => $viewerId,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $likesCount = CommentLike::where('comment_id', $commentId)->count();

            return response()->json([
                'liked' => true,
                'likes_count' => $likesCount
            ]);
        }
    }
    

}
