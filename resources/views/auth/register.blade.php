@extends('layouts.app')

@section('title', 'ثبت‌نام')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-green-100">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-200">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                        <circle cx="10" cy="10" r="3" fill="currentColor"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">ثبت‌نام در سیستم</h2>
                <p class="text-gray-600 mt-2">حساب کاربری خود را ایجاد کنید</p>
            </div>

            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('ثبت‌نام در Build Mode فعال می‌شود')" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        placeholder="نام و نام خانوادگی خود را وارد کنید">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        placeholder="example@worldcup.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">رمز عبور</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        placeholder="حداقل 8 کاراکتر">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        placeholder="رمز عبور را دوباره وارد کنید">
                </div>

                <div class="flex items-start">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                    <label for="terms" class="mr-2 block text-sm text-gray-700">
                        قوانین و مقررات سیستم پیش‌بینی را می‌پذیرم
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white py-3 px-4 rounded-xl font-bold text-lg hover:from-green-700 hover:to-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-[1.02]">
                    ثبت‌نام
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    قبلاً ثبت‌نام کرده‌اید؟
                    <a href="{{ route('login') }}" class="font-medium text-green-700 hover:text-green-800">
                        وارد شوید
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection