<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ], [
            'login.required'    => 'ایمیل یا نام کاربری الزامی است.',
            'password.required' => 'رمز عبور الزامی است.',
        ]);

        $login = $request->input('login');

        // Determine if it's email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $login)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'اطلاعات ورود اشتباه است.']);
        }

        if (!$user->is_active) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'حساب کاربری شما غیرفعال است.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
