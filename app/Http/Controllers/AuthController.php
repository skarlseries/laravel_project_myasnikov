<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Отправка формы регистрации клиенту
    public function create()
    {
        return view('auth.signin');
    }

    // Сохранение нового пользователя в БД с ролью "Читатель" -> redirect на форму авторизации
    public function registration(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|min:2|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Находим роль "reader" (Читатель) в таблице roles
        $readerRole = Role::where('name', 'reader')->first();

        User::create([
            'role_id'  => $readerRole?->id,
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Регистрация прошла успешно! Вам назначена роль «Читатель». Теперь войдите в систему.');
    }

    // Отправка формы авторизации клиенту
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Аутентификация пользователя с присвоением токена Sanctum -> redirect на главную
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Неверный email или пароль.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        $request->session()->put('auth_token', $token);

        return redirect()
            ->route('home')
            ->with('success', 'Вы успешно вошли в систему!');
    }

    // Выход пользователя: удаление токенов, аннулирование сессии, обновление CSRF-токена
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Вы вышли из аккаунта.');
    }
}
