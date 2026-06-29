<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'username'   => ['required', 'string', 'min:3', 'max:50', 'alpha_num', 'unique:users,username'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'department' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required'       => 'نام الزامی است.',
            'username.required'   => 'نام کاربری الزامی است.',
            'username.alpha_num'  => 'نام کاربری فقط می‌تواند شامل حروف و عدد باشد.',
            'username.min'        => 'نام کاربری باید حداقل ۳ کاراکتر باشد.',
            'username.unique'     => 'این نام کاربری قبلاً ثبت شده است.',
            'email.required'      => 'ایمیل الزامی است.',
            'email.unique'        => 'این ایمیل قبلاً ثبت شده است.',
            'password.required'   => 'رمز عبور الزامی است.',
            'password.min'        => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed'  => 'تکرار رمز عبور مطابقت ندارد.',
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'username'   => strtolower($validated['username']),
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'department' => $validated['department'] ?? null,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
