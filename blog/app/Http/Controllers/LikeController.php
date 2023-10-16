<?php

namespace App\Http\Controllers;

use App\Models\Like;
use http\Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class LikeController extends Controller
{
    public function store()
    {
        $data = \request()->validate([
            'user_id' => 'required|integer',
            'post_id' => 'required|integer',
        ]);

        $like = Like::withTrashed()->where('user_id', '=', $data['user_id'])->where('post_id', '=', $data['post_id'])->get();

        if (count($like) == 0) {
            Like::create([
                'user_id' => $data['user_id'],
                'post_id' => $data['post_id']
            ]);
        } else {
            $like[0]->restore();
        }

        return $like;
    }

    public function destroy(Like $like) {
        $like->delete();

        return "Like deleted";
    }

//    public function destroy() {
//        $data = \request()->validate([
//            'user_id' => 'required|integer',
//            'post_id' => 'required|integer',
//        ]);
//
//        $like = Like::where('user_id', '=', $data['user_id'] )->where('post_id', '=', $data['post_id'])->get();
//
//        $like->delete();
//
//        return 'Like deleted';
//    }
}
