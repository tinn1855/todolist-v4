<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function index(Request $request) {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }

            $todos = $user->todos()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Todos retrieved successfully',
                'data' => $todos,
                'count' => $todos->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve todos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
