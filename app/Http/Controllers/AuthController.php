<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // ۱. نمایش فرم ورود
    public function showLogin()
    {
        return view('auth.login');
    }

    // ۲. پردازش اطلاعات ورود
    public function login(Request $request)
    {
        // دیتای تستی که در کدهای اولیه‌ات بود
        $credentials = [
            'user@worldcup.com' => 'user123',
            'ahmad@worldcup.com' => 'ahmad123',
            'sara@worldcup.com' => 'sara123',
            'reza@worldcup.com' => 'reza123'
        ];
        
        if (isset($credentials[$request->email]) && $credentials[$request->email] === $request->password) {
            
            // تولید یک ID فرضی برای اینکه دیتابیس ارور ندهد (تا زمانی که جدول Users را داینامیک کنیم)
            $fakeUserId = array_search($request->email, array_keys($credentials)) + 1;

            session([
                'user_logged_in' => true,
                'user_name' => explode('@', $request->email)[0],
                'user_email' => $request->email,
                'user_id' => $fakeUserId
            ]);
            
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors(['email' => 'اطلاعات ورود نادرست است']);
    }

    // ۳. نمایش فرم ثبت‌نام
    public function showRegister()
    {
        return view('auth.register');
    }

    // ۴. خروج از حساب کاربری
    public function logout()
    {
        session()->forget(['user_logged_in', 'user_name', 'user_email', 'user_id']);
        return redirect()->route('home');
    }
}