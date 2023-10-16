<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index() {
        $user = Auth::user();
        $goods = Good::where('user_id', $user->id)->orderByDesc('id')->get();
        return view('admin.index', compact('user', 'goods'));
    }
}
