<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('comment.index')) {
            $comments = Comment::with('user', 'product')->get();
            return response()->json(['comments' => $comments]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ندارید'], 403);
        }
    }

    public function store(CommentRequest $request)
    {
        if ($request->user()->can('comment.store')) {
            $comment = Comment::create($request->toArray());
            return response()->json(['message' => 'نظر با موفقیت ایجاد شد', 'comment' => $comment], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندرید'], 403);
        }
    }

    public function show(Request $request, $id)
    {
        if ($request->user()->can('comment.show')) {
            $comment = Comment::with('user', 'product')->findOrFail($id);
            return response()->json(['comment' => $comment]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار ار ندارید'], 403);
        }
    }

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

    public function restore(Request $request, $id)
    {
        if ($request->user()->can('comment.restore')) {
            $user = Comment::onlyTrashed()->findOrFail($id);
            $user->restore();
            return response()->json(['message' => 'نظر با موفقیت بازیابی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را دارید'], 403);
        }
    }
}
