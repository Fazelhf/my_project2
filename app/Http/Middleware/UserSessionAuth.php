<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserSessionAuth
{
    /**
     * بررسی می‌کنیم که آیا کاربر لاگین کرده یا نه.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // اگر سشن لاگین وجود نداشت، پرتش کن بیرون به صفحه لاگین
        if (!session('user_logged_in')) {
            return redirect()->route('login')->withErrors(['email' => 'لطفاً ابتدا وارد حساب کاربری خود شوید.']);
        }

        // اگر لاگین بود، اجازه بده بره مرحله بعدی (باز شدن داشبورد)
        return $next($request);
    }
}