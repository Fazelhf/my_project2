@extends('layouts.guest')
@section('title', 'ثبت‌نام')

@section('content')

<h2 class="font-heading text-2xl font-black text-white mb-6 text-right">ایجاد حساب کاربری</h2>

@if($errors->any())
    <div class="flex items-start gap-3 rounded-2xl px-4 py-3 mb-5 flash-error">
        <span class="material-symbols-outlined text-base mt-0.5 flex-shrink-0">error</span>
        <ul class="text-sm space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register.attempt') }}" method="POST" class="space-y-4" autocomplete="off">
    @csrf

    <div class="relative floating-label-input">
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               required placeholder=" " class="stitch-input">
        <label for="name" class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">نام و نام‌خانوادگی</label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.4);">badge</span>
    </div>

    <div class="relative floating-label-input">
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               required placeholder=" " class="stitch-input">
        <label for="email" class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">آدرس ایمیل</label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.4);">alternate_email</span>
    </div>

    <div class="relative floating-label-input">
        <input type="text" id="department" name="department" value="{{ old('department') }}"
               placeholder=" " class="stitch-input">
        <label for="department" class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">دپارتمان / تیم (اختیاری)</label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.4);">corporate_fare</span>
    </div>

    <div class="relative floating-label-input">
        <input type="password" id="password" name="password"
               required placeholder=" " class="stitch-input">
        <label for="password" class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">رمز عبور (حداقل ۸ کاراکتر)</label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.4);">lock</span>
    </div>

    <div class="relative floating-label-input">
        <input type="password" id="password_confirmation" name="password_confirmation"
               required placeholder=" " class="stitch-input">
        <label for="password_confirmation" class="absolute right-4 top-4 text-sm pointer-events-none origin-right transition-all"
               style="color:rgba(185,203,185,0.5);">تکرار رمز عبور</label>
        <span class="material-symbols-outlined absolute left-4 top-4 text-base pointer-events-none"
              style="color:rgba(185,203,185,0.4);">lock_reset</span>
    </div>

    <button type="submit" class="btn-primary w-full py-4 text-base mt-2">
        <span>ایجاد حساب</span>
        <span class="material-symbols-outlined text-base">person_add</span>
    </button>
</form>

<div class="mt-6 pt-5 text-center" style="border-top:1px solid rgba(255,255,255,0.08);">
    <p class="text-sm" style="color:rgba(185,203,185,0.7);">
        حساب دارید؟
        <a href="{{ route('login') }}" class="font-bold" style="color:#00e476;">وارد شوید</a>
    </p>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.floating-label-input input').forEach(input => {
    const label = input.nextElementSibling;
    if (!label || label.tagName !== 'LABEL') return;
    const update = () => {
        const filled = input.value.length > 0;
        label.style.transform  = filled ? 'translateY(-1.4rem) scale(0.82)' : '';
        label.style.color      = input === document.activeElement ? '#00e476' : (filled ? 'rgba(185,203,185,0.7)' : 'rgba(185,203,185,0.5)');
        label.style.background = filled ? '#161c25' : '';
        label.style.padding    = filled ? '0 4px' : '';
    };
    input.addEventListener('focus', update);
    input.addEventListener('blur', update);
    input.addEventListener('input', update);
    update();
});
</script>
@endpush
