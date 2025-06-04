<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|alpha_dash',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Sai tài khoản hoặc mật khẩu.',
            ], 401);
        }

        // Xoá token cũ (nếu cần logout các thiết bị khác)
        $user->tokens()->delete();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
            ],
        ]);
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.'
        ]);
    }

    // Kiểm tra trạng thái đăng nhập
    public function getLoginStatus(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'isLogin' => false,
                'message' => 'Chưa đăng nhập.'
            ]);
        }

        return response()->json([
            'isLogin' => true,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }
}
