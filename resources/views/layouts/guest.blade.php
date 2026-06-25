<!DOCTYPE html>
<html class="dark" lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WorldCup Predictor') — WCP 2026</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&family=Vazirmatn:wght@400;700&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface": "#0e141d",
                        "surface-dim": "#0e141d",
                        "surface-container": "#1a2029",
                        "surface-container-low": "#161c25",
                        "surface-container-lowest": "#080e17",
                        "surface-container-high": "#242a34",
                        "on-surface": "#dde2f0",
                        "on-surface-variant": "#b9cbb9",
                        "primary": "#f0ffee",
                        "primary-container": "#00ff85",
                        "primary-fixed-dim": "#00e476",
                        "on-primary": "#003919",
                        "on-primary-fixed": "#00210c",
                        "secondary": "#b2c7f3",
                        "secondary-container": "#32476c",
                        "tertiary-container": "#ffdc49",
                        "outline": "#849584",
                        "outline-variant": "#3b4b3d",
                        "error": "#ffb4ab",
                    },
                    fontFamily: {
                        "sans": ["Vazirmatn", "sans-serif"],
                        "heading": ["Plus Jakarta Sans", "Vazirmatn", "sans-serif"],
                        "mono": ["JetBrains Mono", "monospace"],
                    },
                },
            },
        }
    </script>
    <style>
        body {
            background-color: #0e141d;
            font-family: 'Vazirmatn', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
        }
        .neon-border {
            position: relative;
        }
        .neon-border::after {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(45deg, transparent, #00e476, transparent, #00e476, transparent);
            background-size: 200% 200%;
            animation: border-glow 4s linear infinite;
            z-index: -1;
            border-radius: 1.5rem;
            opacity: 0.5;
        }
        @keyframes border-glow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
        .liquid-button {
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            background: #00e476;
        }
        .liquid-button::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 300%; height: 300%;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 40%;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.6s ease;
        }
        .liquid-button:hover::before {
            transform: translate(-50%, -50%) scale(1);
            animation: rotate-liquid 4s linear infinite;
        }
        @keyframes rotate-liquid {
            0% { transform: translate(-50%, -50%) rotate(0deg) scale(1); }
            100% { transform: translate(-50%, -50%) rotate(360deg) scale(1); }
        }
        .mesh-background {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            opacity: 0.4;
            pointer-events: none;
        }
        .stadium-line {
            stroke: #00e476;
            stroke-width: 0.5;
            stroke-dasharray: 4;
            animation: dash 20s linear infinite;
        }
        @keyframes dash { to { stroke-dashoffset: -100; } }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .floating-label-input input:focus ~ label,
        .floating-label-input input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #00e476;
            background: #1a2029;
            padding: 0 4px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="mesh-background">
        <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
                <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(0, 228, 118, 0.1)" stroke-width="0.1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
            <circle class="stadium-line" cx="50" cy="50" r="30" fill="none" opacity="0.3"/>
            <rect class="stadium-line" x="10" y="20" width="80" height="60" fill="none" opacity="0.2"/>
        </svg>
    </div>

    <main class="relative z-10 w-full max-w-md px-6 py-10">

        <div class="text-center mb-8">
            <h1 class="font-heading text-4xl font-black text-primary-fixed-dim drop-shadow-[0_0_15px_rgba(0,228,118,0.4)] tracking-tighter">
                پیش‌بینی ۲۰۲۶
            </h1>
            <p class="text-on-surface-variant mt-2">جام جهانی فوتبال در دستان شما</p>
        </div>

        @yield('content')

        <div class="mt-8 flex justify-center gap-8 opacity-60">
            <div class="text-center">
                <div class="font-heading font-bold text-primary-fixed-dim text-xl">۱.۲M+</div>
                <div class="text-xs text-on-surface-variant font-mono">کاربر فعال</div>
            </div>
            <div class="text-center">
                <div class="font-heading font-bold text-primary-fixed-dim text-xl">۴۸</div>
                <div class="text-xs text-on-surface-variant font-mono">تیم ملی</div>
            </div>
            <div class="text-center">
                <div class="font-heading font-bold text-primary-fixed-dim text-xl">۱۰۴</div>
                <div class="text-xs text-on-surface-variant font-mono">مسابقه</div>
            </div>
        </div>

    </main>

    @stack('scripts')
</body>
</html>
