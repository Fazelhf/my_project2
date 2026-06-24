@extends('layouts.guest')

@section('title', 'ثبت‌نام')

@section('content')

<div class="text-center mb-8">
    <h2 class="text-2xl font-bold" style="font-family:'Poppins',sans-serif; color:#F8FAFC;">ثبت‌نام</h2>
    <p class="text-sm mt-2" style="color:#94A3B8;">حساب کاربری خود را ایجاد کنید</p>
</div>

@if($errors->any())
    <div class="mb-6 px-4 py-3 rounded-lg text-sm" style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register.attempt') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">نام و نام خانوادگی</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="نام کامل شما">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">ایمیل</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="name@company.com">
    </div>

    <div>
        <label for="department" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">
            دپارتمان
            <span class="text-xs mr-1" style="color:#475569;">(اختیاری)</span>
        </label>
        <input type="text" id="department" name="department" value="{{ old('department') }}"
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="مثلاً: فناوری اطلاعات">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">رمز عبور</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="حداقل ۸ کاراکتر">
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">تکرار رمز عبور</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required
               class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-colors"
               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
               onfocus="this.style.borderColor='#22C55E';"
               onblur="this.style.borderColor='#334155';"
               placeholder="رمز عبور را دوباره وارد کنید">
    </div>

    <button type="submit"
            class="w-full py-3 rounded-xl text-sm font-bold transition-colors duration-150 cursor-pointer mt-2"
            style="background-color:#22C55E; color:#020617;"
            onmouseover="this.style.backgroundColor='#16A34A';"
            onmouseout="this.style.backgroundColor='#22C55E';">
        ثبت‌نام
    </button>
</form>

<p class="mt-6 text-center text-sm" style="color:#94A3B8;">
    قبلاً ثبت‌نام کرده‌اید؟
    <a href="{{ route('login') }}"
       style="color:#22C55E; font-weight:600;"
       onmouseover="this.style.color='#86efac';"
       onmouseout="this.style.color='#22C55E';">
        وارد شوید
    </a>
</p>

@endsection
