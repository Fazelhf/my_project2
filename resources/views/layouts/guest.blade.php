<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ورود') — WorldCup Predictor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color:#020617; color:#F8FAFC; font-family:'Open Sans',ui-sans-serif,sans-serif;">

<div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

    {{-- Brand --}}
    <div class="mb-8 flex flex-col items-center gap-3">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color:#22C55E;">
            <svg class="w-8 h-8 text-black" fill="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.5"/>
            </svg>
        </div>
        <div class="text-center">
            <h1 class="text-xl font-bold" style="font-family:'Poppins',sans-serif; color:#F8FAFC;">WorldCup Predictor</h1>
            <p class="text-sm mt-0.5" style="color:#94A3B8;">پیش‌بینی جام جهانی ۲۰۲۶</p>
        </div>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-md rounded-2xl p-8 border" style="background-color:#0F172A; border-color:#334155;">
        @yield('content')
    </div>

</div>

@stack('scripts')
</body>
</html>
