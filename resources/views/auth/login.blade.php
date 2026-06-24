@extends('layouts.guest')
@section('title', 'ورود به سیستم')

@section('content')

<h2 class="text-xl font-bold font-heading text-brand-text mb-1">خوش آمدید</h2>
<p class="text-sm text-brand-muted mb-7">با حساب کاربری خود وارد شوید</p>

@if($errors->any())
    <div class="flex items-start gap-2.5 bg-red-950/60 border border-red-800/50 text-red-300 text-sm rounded-xl px-4 py-3 mb-6">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

@if(session('success'))
    <div class="flex items-start gap-2.5 bg-green-950/60 border border-green-800/50 text-green-300 text-sm rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">
            ایمیل
        </label>
        <input
            type="email" id="email" name="email"
            value="{{ old('email') }}"
            required autofocus
            placeholder="name@company.com"
            class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                   placeholder:text-brand-subtle outline-none
                   focus:border-brand-green focus:ring-2 focus:ring-brand-green/20
                   transition-all duration-150"
        >
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-xs font-semibold text-brand-muted uppercase tracking-wider">
                رمز عبور
            </label>
        </div>
        <input
            type="password" id="password" name="password"
            required
            placeholder="••••••••"
            class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                   placeholder:text-brand-subtle outline-none
                   focus:border-brand-green focus:ring-2 focus:ring-brand-green/20
                   transition-all duration-150"
        >
    </div>

    <div class="flex items-center gap-2.5">
        <input
            id="remember" name="remember" type="checkbox"
            class="w-4 h-4 rounded border-brand-border bg-brand-card accent-brand-green cursor-pointer"
        >
        <label for="remember" class="text-sm text-brand-muted cursor-pointer select-none">
            مرا به خاطر بسپار
        </label>
    </div>

    <button
        type="submit"
        class="w-full bg-brand-green hover:bg-brand-green-dim text-black text-sm font-bold
               py-3 rounded-xl transition-colors duration-150 cursor-pointer
               focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:ring-offset-2 focus:ring-offset-brand-surface"
    >
        ورود به سیستم
    </button>
</form>

<p class="text-center text-sm text-brand-muted mt-6">
    حساب کاربری ندارید؟
    <a href="{{ route('register') }}" class="text-brand-green hover:text-green-400 font-semibold transition-colors">
        ثبت‌نام کنید
    </a>
</p>

@endsection
