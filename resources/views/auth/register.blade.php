@extends('layouts.guest')
@section('title', 'ثبت‌نام')

@section('content')

<h2 class="text-xl font-bold font-heading text-brand-text mb-1">ثبت‌نام</h2>
<p class="text-sm text-brand-muted mb-7">حساب کاربری خود را بسازید</p>

@if($errors->any())
    <div class="bg-red-950/60 border border-red-800/50 text-red-300 text-sm rounded-xl px-4 py-3 mb-6 space-y-1">
        @foreach($errors->all() as $error)
            <p class="flex items-center gap-2">
                <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                {{ $error }}
            </p>
        @endforeach
    </div>
@endif

<form action="{{ route('register.attempt') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">نام و نام خانوادگی</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               placeholder="نام کامل شما"
               class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                      placeholder:text-brand-subtle outline-none
                      focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all duration-150">
    </div>

    <div>
        <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">ایمیل</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               placeholder="name@company.com"
               class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                      placeholder:text-brand-subtle outline-none
                      focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all duration-150">
    </div>

    <div>
        <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">
            دپارتمان
            <span class="text-brand-subtle font-normal normal-case tracking-normal mr-1">(اختیاری)</span>
        </label>
        <input type="text" name="department" value="{{ old('department') }}"
               placeholder="مثلاً: فناوری اطلاعات"
               class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                      placeholder:text-brand-subtle outline-none
                      focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all duration-150">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">رمز عبور</label>
            <input type="password" name="password" required
                   placeholder="حداقل ۸ کاراکتر"
                   class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                          placeholder:text-brand-subtle outline-none
                          focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all duration-150">
        </div>
        <div>
            <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">تکرار رمز</label>
            <input type="password" name="password_confirmation" required
                   placeholder="••••••••"
                   class="w-full bg-brand-card border border-brand-border text-brand-text text-sm rounded-xl px-4 py-3
                          placeholder:text-brand-subtle outline-none
                          focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all duration-150">
        </div>
    </div>

    <button type="submit"
            class="w-full bg-brand-green hover:bg-brand-green-dim text-black text-sm font-bold
                   py-3 rounded-xl transition-colors duration-150 cursor-pointer mt-2
                   focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:ring-offset-2 focus:ring-offset-brand-surface">
        ساخت حساب کاربری
    </button>
</form>

<p class="text-center text-sm text-brand-muted mt-6">
    قبلاً ثبت‌نام کرده‌اید؟
    <a href="{{ route('login') }}" class="text-brand-green hover:text-green-400 font-semibold transition-colors">
        وارد شوید
    </a>
</p>

@endsection
