<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Регистрация пользователя с ролью reader и выдачей Sanctum-токена. */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $readerRole = Role::query()->where('name', 'reader')->first();

        if (! $readerRole) {
            return response()->json([
                'status' => 'error',
                'message' => 'Роль reader не найдена. Проверьте заполнение таблицы roles.',
            ], 500);
        }

        $user = User::create([
            'role_id' => $readerRole->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('postman-api')->plainTextToken;
        $user->load('role');

        return response()->json([
            'status' => 'success',
            'message' => 'Пользователь успешно зарегистрирован.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /** Проверка учётных данных и создание Sanctum-токена. */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()
            ->with('role')
            ->where('email', $validated['email'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверный email или пароль.',
            ], 401);
        }

        $token = $user->createToken('postman-api')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Авторизация выполнена успешно.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /** Отзыв текущего Sanctum-токена. */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentToken = $user?->currentAccessToken();

        if ($currentToken && method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Выход из API выполнен успешно.',
        ]);
    }

    /** Данные текущего авторизованного пользователя. */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()->load('role'),
        ]);
    }
}
