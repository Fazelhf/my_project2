@extends('layouts.guest')
@section('title', 'ورود به سیستم')

@section('content')

<h2 class="font-heading text-2xl font-black text-white mb-7 text-right">ورود به پنل پیش‌بینی</h2>

@if($errors->any())
    <div class="flex items-start gap-3 rounded-2xl px-4 py-3 mb-5 flash-error">
        <span class="material-symbols-outlined text-base mt-0.5 flex-shrink-0">error</span>
        <span class="text-sm">{{ $errors->first() }}</span>
    </div>
@endif

@if(session('success'))
    <div class="flex items-start gap-3 rounded-2xl px-4 py-3 mb-5 flash-success">
        <span class="material-symbols-outlined text-base mt-0.5 flex-shrink-0">check_circle</span>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('login.attempt') }}" method="POST" class="space-y-5" id="loginForm">
    @csrf

    {{-- Login (email or username) --}}
    <div class="relative floating-label-input">
        <input type="text" id="login" name="login" value="{{ old('login') }}"
               required autofocus placeholder=" "
               class="stitch-input pr-12"
               style="padding-right:44px;">
        <label for="login"
               class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">
            ایمیل یا نام کاربری
        </label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.45);">person</span>
    </div>

    {{-- Password --}}
    <div class="relative floating-label-input">
        <input type="password" id="password" name="password"
               required placeholder=" "
               class="stitch-input pr-12"
               style="padding-right:44px;">
        <label for="password"
               class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">
            رمز عبور
        </label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base cursor-pointer transition-colors"
              id="togglePassword"
              style="color:rgba(185,203,185,0.45);"
              onmouseover="this.style.color='#00e476'" onmouseout="this.style.color='rgba(185,203,185,0.45)'">visibility</span>
    </div>

    {{-- Remember me --}}
    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember"
               class="w-4 h-4 rounded cursor-pointer"
               style="accent-color:#00e476;">
        <label for="remember" class="text-sm cursor-pointer"
               style="color:rgba(185,203,185,0.7);">مرا به خاطر بسپار</label>
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="btn-primary w-full py-4 text-base"
            id="submitBtn">
        <span>ورود به حساب</span>
        <span class="material-symbols-outlined text-base">login</span>
    </button>
</form>

<div class="mt-7 pt-6 text-center" style="border-top:1px solid rgba(255,255,255,0.08);">
    <p class="text-sm" style="color:rgba(185,203,185,0.7);">
        حساب کاربری ندارید؟
        <a href="{{ route('register') }}" class="font-bold"
           style="color:#00e476;"
           onmouseover="this.style.textDecoration='underline'"
           onmouseout="this.style.textDecoration='none'">ثبت‌نام کنید</a>
    </p>
</div>

@endsection

@push('scripts')
<script>
    /* Floating labels */
    document.querySelectorAll('.floating-label-input input').forEach(input => {
        const label = input.nextElementSibling;
        if (!label || label.tagName !== 'LABEL') return;
        const update = () => {
            const filled = input.value.length > 0;
            label.style.transform = filled ? 'translateY(-1.4rem) scale(0.82)' : '';
            label.style.color = input === document.activeElement ? '#00e476' : (filled ? 'rgba(185,203,185,0.7)' : 'rgba(185,203,185,0.5)');
            label.style.background = filled ? '#161c25' : '';
            label.style.padding = filled ? '0 4px' : '';
        };
        input.addEventListener('focus', update);
        input.addEventListener('blur', update);
        input.addEventListener('input', update);
        update();
    });

    /* Password toggle */
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleBtn.textContent = isPassword ? 'visibility_off' : 'visibility';
        });
    }

    /* Mouse parallax on card */
    const card = document.querySelector('.liquid-glass');
    if (card) {
        document.addEventListener('mousemove', e => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 60;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 60;
            card.style.transform = `perspective(800px) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
        document.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.transition = 'transform 0.5s ease';
        });
    }
</script>
@endpush
