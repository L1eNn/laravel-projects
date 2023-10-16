<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Good;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::where('user_id', Auth::id())->with('good')->get();
        return view('orders.index', compact('orders'));
    }

    public function adminOrders() {
        $adminGoods = Good::where('user_id', Auth::id())->get();
        $orders = [];
        foreach ($adminGoods as $good) {
            $order = Order::where('good_id', $good->id)->with('good')->get();
            if (count($order)) {
                array_push($orders, $order[0]);
            }
        }

        return view('admin.orders', compact('orders'));
    }

    public function create() {
        return view('orders.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'address' => 'string|required',
            'first_name' => 'string|required',
            'second_name' => 'string|required',
            'third_name' => 'string|required',
        ]);

        $basketGoods = Basket::where('user_id', Auth::id())->where('is_purchased', false)->get();

        foreach($basketGoods as $basketGood) {
            Order::create([
                'user_id' => Auth::id(),
                'good_id' => $basketGood->good->id,
                'address' => $data['address'],
                'first_name' => $data['first_name'],
                'second_name' => $data['second_name'],
                'third_name' => $data['third_name'],
                'status' => "The order is being considered"
            ]);

            $basketGood->update([
                'is_purchased' => true
            ]);
        }

        return redirect(route('orders.index'));
    }

    public function update(Request $request, Order $order) {
        $data = $request->validate([
            'status' => 'string|required'
        ]);

        $order->update([
            'status' => $data['status']
        ]);

        return back();
    }
}
