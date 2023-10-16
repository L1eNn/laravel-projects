<?php

namespace App\Http\Controllers;

use App\Models\Good;
use http\Message;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\error;

class GoodController extends Controller
{
    public function index() {
        $goods = Good::all()->reverse();

        return view('goods.index', compact('goods'));
    }

    public function create() {
        return view('goods.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'title' => 'string|required',
            'description' => 'string|min:100|required',
            'price' => 'integer|required'
        ]);

        $images = [];

        for ($i = 1; $i < 6; $i++) {
            if ($request->hasFile("image_$i")) {
                $file = $request->file("image_$i");
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('images'), date('Y-m-d H-i-s').' '.$fileName);
                array_push($images, $fileName);
            }
        }

        if (count($images) === 0) {
            return redirect()->back()->with('alert', 'Images has to be added');
        }

        Good::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'images' => json_encode($images),
            'price' => $data['price']
        ]);

        return redirect('admin/index');
    }

    public function edit(Good $good) {
        return view('goods.edit', compact('good'));
    }

    public function update(Request $request, Good $good) {
        $data = $request->validate([
            'title' => 'string|required',
            'description' => 'string|min:100|required',
            'price' => 'integer|required'
        ]);

        $images = [];

        for ($i = 1; $i < 6; $i++) {
            if ($request->hasFile("image_$i")) {
                $file = $request->file("image_$i");
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('images'), date('Y-m-d H-i-s').' '.$fileName);
                array_push($images, $fileName);
            }
        }

        if (count($images) === 0) {
            return redirect()->back()->with('alert', 'Images has to be added');
        }

        $good->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'images' => json_encode($images),
            'price' => $data['price']
        ]);

        return redirect('admin/index');
    }

    public function destroy(Good $good) {
        $good->delete();
        return redirect(route('admin.index'));
    }
}
