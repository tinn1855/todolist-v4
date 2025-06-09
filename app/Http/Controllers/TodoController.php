<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    // Lấy danh sách todos
    public function index(Request $request)
    {
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

    // Tạo mới todo
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'priority' => 'nullable|in:low,medium,high',
                'status' => 'nullable|in:incomplete,inprogress,completed',
            ]);

            $validated['user_id'] = $request->user()->id;

            $todo = Todo::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Todo created successfully',
                'data' => $todo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật todo
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'string',
                'description' => 'nullable|string',
                'priority' => 'nullable|in:low,medium,high',
                'status' => 'nullable|in:incomplete,inprogress,completed',
            ]);

            $todo = Todo::where('id', $id)->where('user_id', $request->user()->id)->first();

            if (!$todo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todo not found'
                ], 404);
            }

            $todo->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Todo updated successfully',
                'data' => $todo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markTodosCompletedByStatus(Request $request) {
        try {
            $user = $request->user();

            if(!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validated =  $request->validate([
                'status' => 'required|in:incomplete,inprogress'
            ]);

            $updatedCount = $user->todos()
                ->where('status', $validated['status'])
                ->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => "Todos with status '{$validated['status']}' marked as completed",
                'updated' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark todos as completed',
                'error' => $e->getMessage()
            ], 500);
        };
    }

    // Xoá todo
    public function destroy(Request $request, $id)
    {
        try {
            $todo = Todo::where('id', $id)->where('user_id', $request->user()->id)->first();

            if (!$todo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todo not found'
                ], 404);
            }

            $todo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todo deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteTodosByStatus(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validated = $request->validate([
                'status' => 'required|in:completed,inprogress,incomplete'
            ]);

            $todoCount = $user->todos()->where('status', $validated['status'])->count();

            if ($todoCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => "No todos found with status '{$validated['status']}'"
                ], 404);
            };

            $deletedCount = $user->todos()
                ->where('status', $validated['status'])
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "Todos with status '{$validated['status']}' deleted successfully",
                'deleted' => $deletedCount
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete todos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
