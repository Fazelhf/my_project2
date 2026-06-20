<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پیش‌بینی جام جهانی - @yield('title', 'سیستم پیش‌بینی نتایج')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
    </style>
    @yield('head')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-green-700 to-green-900 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-900" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                                <circle cx="10" cy="10" r="3" fill="currentColor"/>
                            </svg>
                        </div>
                        <span class="text-white font-bold text-xl">پیش‌بینی جام جهانی</span>
                    </a>
                    
                    @if(session('user_logged_in'))
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            داشبورد
                        </a>
                        <a href="{{ route('tournament.prediction') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('tournament.prediction') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            پیش‌بینی کل جام
                        </a>
                        <a href="{{ route('matches') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('matches') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            بازی‌ها
                        </a>
                        <a href="{{ route('leaderboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('leaderboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                            جدول رتبه‌بندی
                        </a>
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-3">
                    @if(session('user_logged_in'))
                        <div class="flex items-center gap-3 bg-white/10 rounded-lg px-4 py-2">
                            <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center">
                                <span class="text-green-900 font-bold text-sm">{{ strtoupper(substr(session('user_name'), 0, 2)) }}</span>
                            </div>
                            <span class="text-white font-medium text-sm hidden sm:block">{{ session('user_name') }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all">
                                خروج
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-green-800 px-6 py-2 rounded-lg font-medium text-sm hover:bg-gray-100 transition-all">
                            ورود
                        </a>
                        <a href="{{ route('register') }}" class="bg-yellow-400 text-green-900 px-6 py-2 rounded-lg font-medium text-sm hover:bg-yellow-500 transition-all">
                            ثبت‌نام
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-900" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                            <circle cx="10" cy="10" r="3" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg">پیش‌بینی جام جهانی</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    سیستم حرفه‌ای پیش‌بینی نتایج مسابقات جام جهانی فوتبال با امتیازدهی دقیق و جدول رتبه‌بندی زنده
                </p>
            </div>
            
            <div>
                <h3 class="font-bold mb-4">دسترسی سریع</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">صفحه اصلی</a></li>
                    <li><a href="{{ route('matches') }}" class="text-gray-400 hover:text-white transition-colors">بازی‌های جام</a></li>
                    <li><a href="{{ route('leaderboard') }}" class="text-gray-400 hover:text-white transition-colors">جدول رتبه‌بندی</a></li>
                    <li><a href="{{ route('tournament.prediction') }}" class="text-gray-400 hover:text-white transition-colors">پیش‌بینی کل جام</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-bold mb-4">راهنما</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">نحوه امتیازدهی</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">قوانین مسابقه</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">سوالات متداول</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">تماس با ما</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="font-bold mb-4">امتیازدهی</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-yellow-400 text-green-900 flex items-center justify-center font-bold text-xs">5</span>
                        <span>نتیجه دقیق</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-green-400 text-green-900 flex items-center justify-center font-bold text-xs">3</span>
                        <span>برنده درست</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-blue-400 text-blue-900 flex items-center justify-center font-bold text-xs">4</span>
                        <span>تفاضل گل</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 py-6 text-center text-sm">
            <p class="text-gray-400">&copy; {{ date('Y') }} سیستم پیش‌بینی جام جهانی. تمامی حقوق محفوظ است.</p>
            <p class="mt-2 text-gray-500">Made with ❤️ by <a href="https://laracopilot.com/" target="_blank" class="hover:text-yellow-400 transition-colors">LaraCopilot</a></p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>