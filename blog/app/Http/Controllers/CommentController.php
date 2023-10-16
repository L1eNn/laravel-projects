<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required',
            'post_id' => 'required',
            'content' => 'string|required|min:1'
        ]);

        return Comment::create($data);
    }

    public function destroy(Comment $comment) {
        $comment->delete();
        $post = Post::find($comment->post_id);
        return $post->user_id;
    }
}
