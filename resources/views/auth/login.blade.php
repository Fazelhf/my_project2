@extends('layouts.guest')
@section('title', 'ورود به سیستم')

@section('content')

<div class="glass-card neon-border rounded-3xl p-8 md:p-10">

    <h2 class="font-heading text-2xl font-bold text-on-surface mb-8 text-right">ورود به پنل پیش‌بینی</h2>

    @if($errors->any())
        <div class="flex items-start gap-3 rounded-xl px-4 py-3 mb-6 bg-error/10 border border-error/30 text-error">
            <span class="material-symbols-outlined text-base mt-0.5">error</span>
            <span class="text-sm">{{ $errors->first() }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="flex items-start gap-3 rounded-xl px-4 py-3 mb-6 bg-primary-container/10 border border-primary-fixed-dim/30 text-primary-fixed-dim">
            <span class="material-symbols-outlined text-base mt-0.5">check_circle</span>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-6" id="loginForm">
        @csrf

        <div class="relative floating-label-input">
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder=" "
                   class="w-full bg-surface-container-lowest/50 border border-white/10 rounded-xl px-4 py-4 text-on-surface outline-none focus:border-primary-fixed-dim transition-all peer">
            <label for="email" class="absolute right-4 top-4 text-on-surface-variant transition-all pointer-events-none origin-right">
                نام کاربری یا ایمیل
            </label>
            <span class="material-symbols-outlined absolute left-4 top-4 text-on-surface-variant">person</span>
        </div>

        <div class="relative floating-label-input">
            <input type="password" id="password" name="password" required
                   placeholder=" "
                   class="w-full bg-surface-container-lowest/50 border border-white/10 rounded-xl px-4 py-4 text-on-surface outline-none focus:border-primary-fixed-dim transition-all peer">
            <label for="password" class="absolute right-4 top-4 text-on-surface-variant transition-all pointer-events-none origin-right">
                رمز عبور
            </label>
            <span class="material-symbols-outlined absolute left-4 top-4 text-on-surface-variant cursor-pointer hover:text-primary-fixed-dim" id="togglePassword">visibility</span>
        </div>

        <div class="flex items-center justify-between text-sm font-mono">
            <label class="flex items-center gap-2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-surface-container-lowest text-primary-fixed-dim focus:ring-primary-fixed-dim focus:ring-offset-0">
                مرا به خاطر بسپار
            </label>
        </div>

        <button type="submit"
                class="liquid-button w-full py-4 rounded-xl font-heading font-bold text-on-primary-fixed flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(0,228,118,0.3)] hover:shadow-[0_0_30px_rgba(0,228,118,0.5)] transition-all"
                id="submitBtn">
            <span>ورود به حساب</span>
            <span class="material-symbols-outlined">login</span>
        </button>
    </form>

    <div class="mt-8 pt-8 border-t border-white/10 text-center">
        <p class="text-on-surface-variant mb-2">
            حساب کاربری ندارید؟
            <a href="{{ route('register') }}" class="text-primary-fixed-dim font-bold hover:underline">ثبت‌نام کنید</a>
        </p>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        toggleBtn.textContent = isPassword ? 'visibility_off' : 'visibility';
    });

    const card = document.querySelector('.glass-card');
    document.addEventListener('mousemove', (e) => {
        const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
        const yAxis = (window.innerHeight / 2 - e.pageY) / 50;
        if (card) card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
    });
</script>
@endpush
