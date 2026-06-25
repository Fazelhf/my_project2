<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function showForm(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'department' => ['nullable', 'string', 'max:100'],
            'password'   => ['required', 'confirmed', 'min:8'],
        ], [
            'name.required'       => 'نام الزامی است.',
            'email.required'      => 'ایمیل الزامی است.',
            'email.email'         => 'فرمت ایمیل صحیح نیست.',
            'email.unique'        => 'این ایمیل قبلاً ثبت شده است.',
            'password.required'   => 'رمز عبور الزامی است.',
            'password.confirmed'  => 'تکرار رمز عبور مطابقت ندارد.',
            'password.min'        => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'department' => $validated['department'] ?? null,
            'password'   => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
