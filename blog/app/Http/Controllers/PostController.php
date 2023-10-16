<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Mockery\Matcher\Any;
use PhpParser\Node\Scalar\String_;
use Ramsey\Uuid\Type\Integer;
use function Laravel\Prompts\text;
use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with(['user', 'likes', 'comments', 'views'])->get();
        return $posts;
    }

    public function getCertainUserPosts(String $id) {
        return Post::where('user_id', $id)->orderBy('id', 'desc')->with('comments', 'likes', 'views')->get();
    }

    public function getCertainPost(String $id) {
        return Post::find($id);
    }

    public function create() {
        $user_id = Auth::user()->id;
        return view('post_create', compact('user_id'));
    }

    public function store() {
        $data = \request()->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string',
            'content' => 'required|string',
            'imageAmount' => 'integer',
        ]);

        $images = [];

        for ($i = 0; $i < $data['imageAmount']; $i++) {
            if (\request()->hasFile("image_$i")) {
                $image = \request()->file("image_$i");
                $image->move(public_path('images'), date('Y-m-d H-i-s').' '.$image->getClientOriginalName());
                array_push($images, $image->getClientOriginalName());
            }
        }

        Post::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'images' => json_encode($images)
        ]);

        return "Post successfully added";
    }

    public function edit(Post $post) {
        return view('post_edit', compact('post'));
    }

    public function show(String $id) {
        return Post::where('id', $id)->with('comments', 'likes', 'views')->first();
    }

    public function update(Post $post) {
        $data = \request()->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'imageAmount' => 'integer'
        ]);

        $images = [];


        $postCreatedDate = str_replace(':', '-', strval($post->created_at));

        for ($i = 0; $i < $data['imageAmount']; $i++) {
            if (\request()->exists("image_$i")) {
                if (\request()->hasFile("image_$i")) {
                    $image = \request()->file("image_$i");
                    $image->move(public_path('images'), $postCreatedDate . ' ' . $image->getClientOriginalName());
                    array_push($images, $image->getClientOriginalName());
                }
                else {
                    array_push($images, \request()->get("image_$i"));
                }
            }
        }

        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'images' => json_encode($images)
        ]);

        return "Post successfully updated";
    }

    public function destroy(Post $post) {
        $post->delete();

        return "Post successfully deleted";
    }
}
