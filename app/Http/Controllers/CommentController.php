<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tạo bình luận
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_slug' => 'required|string',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'movie_slug' => $request->movie_slug,
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        return redirect()->back()->with('success', 'Bình luận đã được gửi');
    }

    /**
     * Xóa bình luận (chỉ admin và chủ bình luận)
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $movieSlug = $comment->movie_slug;
        $comment->delete();

        return redirect("phim/{$movieSlug}")->with('success', 'Bình luận đã được xóa');
    }
}
