<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function index() {
        $goodsInBasket = Basket::where('user_id', Auth::id())->where('is_purchased', false)->with('good')->get();
        $totalPrice = 0;
        foreach ($goodsInBasket as $basketGood) {
            $totalPrice += $basketGood->good->price;
        }
        return view('basket.index', compact('goodsInBasket', 'totalPrice'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'good_id' => 'required|string',
        ]);

        Basket::create([
            'user_id' => Auth::id(),
            'good_id' => $data['good_id']
        ]);

        return back();
    }

    public function destroy(Basket $basket) {
        $basket->delete();
        return back();
    }
}
