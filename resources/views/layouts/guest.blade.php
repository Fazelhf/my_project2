<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ورود') — WorldCup Predictor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-brand-bg text-brand-text font-sans antialiased min-h-screen flex items-center justify-center px-4 py-12">

    {{-- Background blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-10 blur-3xl bg-brand-green"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full opacity-5 blur-3xl bg-brand-blue"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-green mb-4 shadow-lg shadow-brand-green/20">
                <svg class="w-9 h-9 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="currentColor" stroke="none"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold font-heading text-brand-text">WorldCup Predictor</h1>
            <p class="text-sm text-brand-muted mt-1">پیش‌بینی جام جهانی ۲۰۲۶</p>
        </div>

        {{-- Card --}}
        <div class="bg-brand-surface border border-brand-border rounded-2xl p-8 shadow-2xl">
            @yield('content')
        </div>

    </div>

    @stack('scripts')
</body>
</html>
