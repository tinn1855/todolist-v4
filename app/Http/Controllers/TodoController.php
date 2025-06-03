<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();

        $todos = Todo::where('user_id', $user->id)->get();

        return response()->json($todos);
    }
}
