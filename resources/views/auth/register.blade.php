@extends('layouts.guest')
@section('title', 'ثبت‌نام')

@section('content')

<div class="mb-7">
    <h2 class="text-2xl font-black font-heading text-brand-text mb-1">ثبت‌نام</h2>
    <p class="text-sm text-brand-muted">حساب کاربری بساز و وارد بازی شو</p>
</div>

@if($errors->any())
    <div class="rounded-xl px-4 py-3 mb-6 space-y-1 score-red">
        @foreach($errors->all() as $error)
            <p class="flex items-center gap-2 text-sm">
                <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                {{ $error }}
            </p>
        @endforeach
    </div>
@endif

<form action="{{ route('register.attempt') }}" method="POST" class="space-y-4">
    @csrf

    @php
    $inputClass = "w-full px-4 py-3 rounded-xl text-sm text-brand-text placeholder:text-brand-subtle outline-none transition-all duration-200";
    $inputStyle = "background: rgba(255,255,255,0.04); border: 1px solid #1E2D45;";
    $focusJs = "onfocus=\"this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'\" onblur=\"this.style.borderColor='#1E2D45'; this.style.boxShadow='none'\"";
    @endphp

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-brand-muted uppercase tracking-widest">نام و نام خانوادگی</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               placeholder="نام کامل شما"
               class="{{ $inputClass }}" style="{{ $inputStyle }}"
               onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
               onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-brand-muted uppercase tracking-widest">ایمیل</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               placeholder="name@company.com"
               class="{{ $inputClass }}" style="{{ $inputStyle }}"
               onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
               onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
    </div>

    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-brand-muted uppercase tracking-widest">
            دپارتمان
            <span class="text-brand-subtle font-normal normal-case tracking-normal mr-1">(اختیاری)</span>
        </label>
        <input type="text" name="department" value="{{ old('department') }}"
               placeholder="مثلاً: فناوری اطلاعات"
               class="{{ $inputClass }}" style="{{ $inputStyle }}"
               onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
               onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-brand-muted uppercase tracking-widest">رمز عبور</label>
            <input type="password" name="password" required placeholder="حداقل ۸ کاراکتر"
                   class="{{ $inputClass }}" style="{{ $inputStyle }}"
                   onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
                   onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
        </div>
        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-brand-muted uppercase tracking-widest">تکرار</label>
            <input type="password" name="password_confirmation" required placeholder="••••••••"
                   class="{{ $inputClass }}" style="{{ $inputStyle }}"
                   onfocus="this.style.borderColor='#F59E0B'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.15)'"
                   onblur="this.style.borderColor='#1E2D45'; this.style.boxShadow='none'">
        </div>
    </div>

    <button type="submit"
            class="w-full py-3.5 rounded-xl text-sm font-black font-heading tracking-wide cursor-pointer transition-all duration-200 mt-2"
            style="background: linear-gradient(135deg, #D97706, #F59E0B, #FCD34D); color: #0a0a0a; box-shadow: 0 0 30px rgba(245,158,11,0.3);"
            onmouseover="this.style.boxShadow='0 0 50px rgba(245,158,11,0.5)'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.boxShadow='0 0 30px rgba(245,158,11,0.3)'; this.style.transform='translateY(0)'">
        ساخت حساب کاربری
    </button>
</form>

<p class="text-center text-sm text-brand-muted mt-6">
    قبلاً ثبت‌نام کرده‌اید؟
    <a href="{{ route('login') }}" class="font-bold transition-colors duration-150"
       style="color: #F59E0B;"
       onmouseover="this.style.color='#FCD34D'"
       onmouseout="this.style.color='#F59E0B'">
        وارد شوید
    </a>
</p>

@endsection
