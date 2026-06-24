@extends('layouts.guest')

@section('title', 'ورود به سیستم')

@section('content')

<div class="text-center mb-8">
    <h2 class="text-2xl font-bold" style="font-family:'Poppins',sans-serif; color:#F8FAFC;">ورود به سیستم</h2>
    <p class="text-sm mt-2" style="color:#94A3B8;">برای شرکت در پیش‌بینی‌ها وارد شوید</p>
</div>

@if($errors->any())
    <div class="mb-6 px-4 py-3 rounded-lg text-sm flex items-start gap-2"
         style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">ایمیل</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="name@company.com">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">رمز عبور</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="رمز عبور خود را وارد کنید">
    </div>

    <div class="flex items-center gap-2">
        <input id="remember" name="remember" type="checkbox"
               class="w-4 h-4 rounded cursor-pointer"
               style="accent-color:#22C55E;">
        <label for="remember" class="text-sm cursor-pointer" style="color:#94A3B8;">مرا به خاطر بسپار</label>
    </div>

    <button type="submit"
            class="w-full py-3 rounded-xl text-sm font-bold transition-colors duration-150 cursor-pointer"
            style="background-color:#22C55E; color:#020617;"
            onmouseover="this.style.backgroundColor='#16A34A';"
            onmouseout="this.style.backgroundColor='#22C55E';">
        ورود به سیستم
    </button>
</form>

<p class="mt-6 text-center text-sm" style="color:#94A3B8;">
    حساب کاربری ندارید؟
    <a href="{{ route('register') }}"
       style="color:#22C55E; font-weight:600;"
       onmouseover="this.style.color='#86efac';"
       onmouseout="this.style.color='#22C55E';">
        ثبت‌نام کنید
    </a>
</p>

@endsection
