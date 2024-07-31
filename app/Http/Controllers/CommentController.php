<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::whih('user', 'product')->get();
        return response()->json(['comments' => $comments]);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create($request->toArray());
        return response()->json(['message' => 'نظر با موفقیت ایجاد شد', 'comment' => $$comment], 201);
    }

    public function show($id)
    {
        $comment = Comment::whit('user', 'product')->findOrFail($id);
        return response()->json(['comment' => '$comment']);
    }

    public function update($UpdateCommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->toArray());
        return response()->json(['message' => 'نظر با موفقیت به روز رسانی شد','comment' => $comment]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment = delete();
        return response()->json(['message' => 'نظر با موفقیت حذف شد']);
    }

    public function restore($id)
    {
        $user = Comment::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['message' => 'نظر با موفقیت بازیابی شد.'], 200);
    }
}
