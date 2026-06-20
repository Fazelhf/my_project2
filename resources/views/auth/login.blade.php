@extends('layouts.app')

@section('title', 'ورود به سیستم')

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
                <h2 class="text-3xl font-bold text-gray-900">ورود به سیستم</h2>
                <p class="text-gray-600 mt-2">به سیستم پیش‌بینی جام جهانی خوش آمدید</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 rounded-lg mb-6">
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm">
                <p class="font-semibold text-blue-900 mb-2">حساب‌های آزمایشی:</p>
                <div class="space-y-1 text-blue-800">
                    <p>ایمیل: <code class="bg-blue-100 px-2 py-0.5 rounded">user@worldcup.com</code></p>
                    <p>رمز عبور: <code class="bg-blue-100 px-2 py-0.5 rounded">user123</code></p>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
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
                        placeholder="رمز عبور خود را وارد کنید">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember" class="mr-2 block text-sm text-gray-700">
                            مرا به خاطر بسپار
                        </label>
                    </div>
                    <a href="#" class="text-sm font-medium text-green-700 hover:text-green-800">
                        فراموشی رمز عبور؟
                    </a>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-green-800 text-white py-3 px-4 rounded-xl font-bold text-lg hover:from-green-700 hover:to-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:scale-[1.02]">
                    ورود به سیستم
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    حساب کاربری ندارید؟
                    <a href="{{ route('register') }}" class="font-medium text-green-700 hover:text-green-800">
                        ثبت‌نام کنید
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection