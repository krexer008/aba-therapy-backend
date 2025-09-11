<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Авторизация пользователя по email b паролю
     * POST /api/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль'],
            ]);
        }

        $user = $request->user();
        $token = $user->createToken('mobile-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Авторизация успешна',
            'user' => $user,
            'access_token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Выход выполнен',
        ]);
    }

    public function send_code(Request $request)
    {
        // Тут ничего нет
        // Заглушка
    }
}
