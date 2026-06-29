@extends('layouts.app')
@section('title', 'پروفایل')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-black font-heading text-white mb-6 flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl" style="color:#00e476;">manage_accounts</span>
        پروفایل من
    </h1>

    @if(session('success'))
    <div class="flex items-center gap-3 rounded-2xl px-4 py-3 mb-5 text-sm font-semibold flash-success">
        <span class="material-symbols-outlined text-base">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 rounded-2xl px-4 py-3 mb-5 flash-error">
        <span class="material-symbols-outlined text-base mt-0.5 flex-shrink-0">error</span>
        <ul class="text-sm space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="glass-card rounded-3xl p-6 space-y-5">

            {{-- Name --}}
            <div>
                <label class="block text-sm font-bold mb-2 text-white">نام و نام‌خانوادگی</label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="stitch-input w-full pr-12" style="padding-right:44px;">
                    <span class="material-symbols-outlined absolute right-4 top-3.5 text-base pointer-events-none" style="color:rgba(185,203,185,0.4);">badge</span>
                </div>
            </div>

            {{-- Username --}}
            <div>
                <label class="block text-sm font-bold mb-2 text-white">
                    نام کاربری
                    <span class="text-xs font-normal mr-2" style="color:rgba(185,203,185,0.5);">فقط حروف انگلیسی و عدد — قابل تغییر</span>
                </label>
                <div class="relative">
                    <input type="text" name="username" value="{{ old('username', $user->username) }}"
                           class="stitch-input w-full pr-12" style="padding-right:44px;" placeholder="مثال: john123">
                    <span class="absolute right-4 top-3.5 text-sm font-bold pointer-events-none" style="color:rgba(185,203,185,0.4);">@</span>
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-bold mb-2 text-white">آدرس ایمیل</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="stitch-input w-full pr-12" style="padding-right:44px;">
                    <span class="material-symbols-outlined absolute right-4 top-3.5 text-base pointer-events-none" style="color:rgba(185,203,185,0.4);">alternate_email</span>
                </div>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.07);"></div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-bold mb-2 text-white">رمز عبور جدید <span class="text-xs font-normal mr-1" style="color:rgba(185,203,185,0.5);">خالی = بدون تغییر</span></label>
                <div class="relative">
                    <input type="password" name="password" id="new_password"
                           class="stitch-input w-full pr-12" style="padding-right:44px;" placeholder="حداقل ۸ کاراکتر">
                    <span class="material-symbols-outlined absolute right-4 top-3.5 text-base pointer-events-none" style="color:rgba(185,203,185,0.4);">lock</span>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold mb-2 text-white">تکرار رمز عبور جدید</label>
                <div class="relative">
                    <input type="password" name="password_confirmation"
                           class="stitch-input w-full pr-12" style="padding-right:44px;">
                    <span class="material-symbols-outlined absolute right-4 top-3.5 text-base pointer-events-none" style="color:rgba(185,203,185,0.4);">lock_reset</span>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5">
                <span class="material-symbols-outlined text-base">save</span>
                ذخیره تغییرات
            </button>

        </div>
    </form>
</div>

@endsection
