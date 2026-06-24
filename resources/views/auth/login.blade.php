@extends('layouts.guest')
@section('title', 'ورود به سیستم')

@section('content')

<div class="mb-7">
    <h2 class="text-2xl font-black font-heading text-brand-text mb-1">خوش آمدید</h2>
    <p class="text-sm text-brand-muted">با حساب کاربری خود وارد شوید</p>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 rounded-xl px-4 py-3 mb-6 score-red">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm">{{ $errors->first() }}</span>
    </div>
@endif

@if(session('success'))
    <div class="flex items-start gap-3 rounded-xl px-4 py-3 mb-6 score-green">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
    @csrf

    <div class="space-y-1.5">
        <label for="email" class="block text-xs font-bold text-brand-muted uppercase tracking-widest">
            ایمیل
        </label>
        <div class="relative">
            <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
            </svg>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="name@company.com"
                   class="w-full pr-10 pl-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200"
                   style="background: rgba(255,255,255,0.04); border: 1px solid #1E2D45;"
                   onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
                   onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
        </div>
    </div>

    <div class="space-y-1.5">
        <label for="password" class="block text-xs font-bold text-brand-muted uppercase tracking-widest">
            رمز عبور
        </label>
        <div class="relative">
            <svg class="absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <input type="password" id="password" name="password" required placeholder="••••••••"
                   class="w-full pr-10 pl-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200"
                   style="background: rgba(255,255,255,0.04); border: 1px solid #1E2D45;"
                   onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
                   onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        <input id="remember" name="remember" type="checkbox"
               class="w-4 h-4 rounded cursor-pointer accent-brand-gold"
               style="border: 1px solid #1E2D45; background: rgba(255,255,255,0.04);">
        <label for="remember" class="text-sm text-brand-muted cursor-pointer select-none">
            مرا به خاطر بسپار
        </label>
    </div>

    <button type="submit"
            class="w-full py-3.5 rounded-xl text-sm font-black font-heading tracking-wide cursor-pointer transition-all duration-200 relative overflow-hidden group"
            style="background: linear-gradient(135deg, #D97706, #F59E0B, #FCD34D); color: #0a0a0a; box-shadow: 0 0 30px rgba(245,158,11,0.3);"
            onmouseover="this.style.boxShadow='0 0 50px rgba(245,158,11,0.5)'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.boxShadow='0 0 30px rgba(245,158,11,0.3)'; this.style.transform='translateY(0)'">
        <span class="relative z-10 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            ورود به سیستم
        </span>
    </button>
</form>

<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full" style="border-top: 1px solid #1E2D45;"></div>
    </div>
    <div class="relative flex justify-center">
        <span class="px-3 text-xs text-brand-subtle" style="background: rgba(10,15,30,0.95);">یا</span>
    </div>
</div>

<p class="text-center text-sm text-brand-muted">
    حساب کاربری ندارید؟
    <a href="{{ route('register') }}" class="font-bold transition-colors duration-150"
       style="color: #F59E0B;"
       onmouseover="this.style.color='#FCD34D'"
       onmouseout="this.style.color='#F59E0B'">
        ثبت‌نام کنید
    </a>
</p>

@endsection
