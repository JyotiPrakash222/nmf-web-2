<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ViewerComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentModerationController extends Controller
{
    // Display blogs with at least 1 comment for moderation
  public function index(Request $request)
{
    $blogs = Blog::with(['category']) // Load category relationship for full_url
        ->withCount('allComments') // This will add all_comments_count attribute
        ->has('allComments')
        ->orderBy('created_at', 'desc');

    if ($request->filled('title')) {
        $blogs->where('name', 'like', '%' . $request->title . '%');
    }

    $perPage = $request->input('perPage', 30);
    $blogs = $blogs->paginate($perPage);

    $queryParams = $request->except('page');
    if ($perPage != 30) {
        $queryParams['perPage'] = $perPage;
    }

    $blogs->setPath(route('admin.comments.index') . '?' . http_build_query($queryParams));

    return view('admin.comments.index', compact('blogs', 'perPage'));
}



    // Show comments for one blog post
    public function show(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $comments = $blog->allComments()
            ->with('viewer')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $comments->where('moderation_status', $request->status);
        }

        $perPage = $request->input('perPage', 30);
        $comments = $comments->paginate($perPage);

        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        $comments->setPath(route('admin.comments.show', $id) . '?' . http_build_query($queryParams));

        return view('admin.comments.show', compact('comments', 'blog', 'perPage'));
    }

    // Edit a specific comment form
    public function edit($id)
    {
        $comment = ViewerComment::findOrFail($id);
        return view('admin.comments.edit', compact('comment'));
    }

    // Update comment and redirect to blog's comments page
    public function update(Request $request, $id)
    {
        Log::info("Attempting to update comment ID: {$id}");

        $request->validate([
            'body' => 'required|min:3|max:1000',
        ]);

        try {
            $comment = ViewerComment::findOrFail($id);
            $comment->body = $request->body;
            $comment->save();

            Log::info("Successfully updated comment ID: {$id}");

            return redirect()->route('admin.comments.show', $comment->commentable_id)
                ->with('success', 'Comment updated successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to update comment ID: {$id}. Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    // Approve comment action
    public function approve($id)
    {
        Log::info("Attempting to approve comment ID: {$id}");

        try {
            $comment = ViewerComment::findOrFail($id);
            $comment->approve(auth()->id());

            Log::info("Successfully approved comment ID: {$id}");
            return redirect()->back()->with('success', 'Comment approved.');
        } catch (\Exception $e) {
            Log::error("Failed to approve comment ID: {$id}. Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    // Reject comment action
    public function reject($id)
    {
        Log::info("Attempting to reject comment ID: {$id}");

        try {
            $comment = ViewerComment::findOrFail($id);
            $comment->reject(auth()->id());

            Log::info("Successfully rejected comment ID: {$id}");
            return redirect()->back()->with('success', 'Comment rejected.');
        } catch (\Exception $e) {
            Log::error("Failed to reject comment ID: {$id}. Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Rejection failed: ' . $e->getMessage());
        }
    }

    // Delete comment action with cascade and detailed logging
    public function destroy($id)
    {
        Log::info("Attempting to delete comment ID: {$id}");

        try {
            $comment = ViewerComment::findOrFail($id);
            Log::info("Found comment ID: {$id}, Body: " . substr($comment->body, 0, 50));

            // Check for related records
            $repliesCount = $comment->replies()->count();
            $likesCount = $comment->likes()->count();

            Log::info("Comment ID: {$id} has {$repliesCount} replies and {$likesCount} likes");

            // Cascade delete replies and likes
            if ($repliesCount > 0) {
                $comment->replies()->delete();
                Log::info("Deleted {$repliesCount} replies for comment ID: {$id}");
            }

            if ($likesCount > 0) {
                $comment->likes()->delete();
                Log::info("Deleted {$likesCount} likes for comment ID: {$id}");
            }

            // Delete the main comment
            $comment->delete();
            Log::info("Successfully deleted comment ID: {$id}");

            return redirect()->back()->with('success', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Failed to delete comment ID: {$id}. Error: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    // Bulk action handler with detailed logging
    public function bulkAction(Request $request)
    {
        $ids = $request->input('selected', []);
        $action = $request->input('action');

        Log::info("Bulk action '{$action}' requested for comment IDs: " . implode(', ', $ids));

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            try {
                $comment = ViewerComment::find($id);
                if (!$comment) {
                    Log::warning("Comment ID {$id} not found for bulk action");
                    $failCount++;
                    $errors[] = "Comment ID {$id} not found";
                    continue;
                }

                Log::info("Processing bulk {$action} for comment ID: {$id}");

                switch ($action) {
                    case 'approve':
                        $comment->approve(auth()->id());
                        $successCount++;
                        break;
                    case 'reject':
                        $comment->reject(auth()->id());
                        $successCount++;
                        break;
                    case 'delete':
                        $comment->replies()->delete();
                        $comment->likes()->delete();
                        $comment->delete();
                        $successCount++;
                        break;
                    default:
                        Log::warning("Unknown bulk action: {$action}");
                        $failCount++;
                        $errors[] = "Unknown action: {$action}";
                }

                Log::info("Successfully completed bulk {$action} for comment ID: {$id}");

            } catch (\Exception $e) {
                Log::error("Bulk {$action} failed for comment ID: {$id}. Error: " . $e->getMessage());
                $failCount++;
                $errors[] = "ID {$id}: " . $e->getMessage();
            }
        }

        $message = "Bulk {$action} completed. Success: {$successCount}";
        if ($failCount > 0) {
            $message .= ", Failed: {$failCount}";
            Log::warning("Bulk action errors: " . implode('; ', $errors));
            return redirect()->back()->with('error', $message);
        }

        Log::info("Bulk action completed successfully: {$message}");
        return redirect()->back()->with('success', $message);
    }
}
