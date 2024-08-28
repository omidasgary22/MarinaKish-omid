<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     * This method returns a JSON response containing all comments with their related user and product data.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('comment.index')) {
            $comments = Comment::with('user', 'product')->where('status', 'approved')->get();
            return response()->json(['comments' => $comments]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    /**
     * Store a newly created comment in storage.
     * This method validates the request and creates a new comment if the user has the required permission.
     */
    public function store(CommentRequest $request)
    {
        if ($request->user()->can('comment.store')) {
            $comment = Comment::create($request->toArray());
            return response()->json(['message' => 'نظر با موفقیت ایجاد شد', 'comment' => $comment], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }

    /**
     * Display the specified comment.
     * This method returns a JSON response containing the comment details along with related user and product data.
     */
    public function show(Request $request, $id)
    {
        if ($request->user()->can('comment.show')) {
            $comment = Comment::with('user', 'product')->findOrFail($id);
            return response()->json(['comment' => $comment]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Update the specified comment in storage.
     * This method validates the request and updates the comment if the user has the required permission.
     */
    public function update(CommentRequest $request, $id)
    {
        if ($request->user()->can('comment.update')) {
            $comment = Comment::findOrFail($id);
            $comment->update($request->toArray());
            return response()->json(['message' => 'نظر با موفقیت به روز رسانی شد', 'comment' => $comment]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }

    /**
     * Remove the specified comment from storage.
     * This method soft deletes the comment if the user has the required permission.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('comment.destroy')) {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return response()->json(['message' => 'نظر با موفقیت حذف شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }

    /**
     * Restore a soft deleted comment.
     * This method restores the comment if the user has the required permission.
     */
    public function restore(Request $request, $id)
    {
        if ($request->user()->can('comment.restore')) {
            $comment = Comment::onlyTrashed()->findOrFail($id);
            $comment->restore();
            return response()->json(['message' => 'نظر با موفقیت بازیابی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }

    public function approve(Request $request, $id)
    {
        if ($request->user()->can('comment.approve')) {
            $comment = Comment::findOrFail($id);
            $comment->status = 'approved';
            $comment->save();
            return response()->json(['message' => 'کامنت با موفقیت تایید شد']);
        } else {
            return response()->json(['message' => 'شما دسترسی لازم برای انجام این کار را ندارید']);
        }
    }
}
