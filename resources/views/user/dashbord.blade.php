@extends('layouts.app')

@section('title', 'داشبورد')

@section('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">خوش آمدید، {{ session('user_name') }}! 👋</h1>
        <p class="text-gray-600">آمار و عملکرد شما در سیستم پیش‌بینی جام جهانی</p>
    </div>

    <!-- Score Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium">امتیاز پیش‌بینی کل جام</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['tournament_score'] }}</p>
            <p class="text-xs font-medium text-yellow-600 mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                +15% نسبت به میانگین
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium">امتیاز بازی‌ها</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['match_score'] }}</p>
            <p class="text-xs font-medium text-green-600 mt-2 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                +8% این هفته
            </p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500 font-medium">امتیاز کل</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_score'] }}</p>
            <p class="text-xs font-medium text-blue-600 mt-2">
                میانگین: {{ number_format($stats['total_score'] / $stats['predictions_made'], 1) }} در هر بازی
            </p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <p class="text-sm text-white/80 font-medium">رتبه شما</p>
            <p class="text-5xl font-bold mt-1">{{ $stats['rank'] }}</p>
            <p class="text-xs font-medium text-white/80 mt-2">
                از {{ number_format($stats['total_users']) }} کاربر
            </p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">پیش‌بینی‌های ثبت شده</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['predictions_made'] }}</p>
                </div>
                <div class="w-16 h-16 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">پیش‌بینی‌های درست</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['correct_predictions'] }}</p>
                </div>
                <div class="w-16 h-16 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">درصد دقت</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['accuracy'] }}%</p>
                </div>
                <div class="w-16 h-16 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart and Recent Matches -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Progress Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 mb-6">روند امتیازات شما</h2>
            <canvas id="scoreChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-6 text-white shadow-lg">
            <h3 class="text-xl font-bold mb-6">اقدامات سریع</h3>
            <div class="space-y-3">
                <a href="{{ route('tournament.prediction') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-4 transition-all">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-medium">پیش‌بینی کل جام</span>
                </a>
                <a href="{{ route('matches') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-4 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">پیش‌بینی بازی‌ها</span>
                </a>
                <a href="{{ route('leaderboard') }}" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 rounded-xl p-4 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium">جدول رتبه‌بندی</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Matches -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">آخرین پیش‌بینی‌های شما</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($recentMatches as $match)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="font-bold text-gray-900 text-lg">{{ $match['team1'] }}</span>
                            <span class="text-gray-400">vs</span>
                            <span class="font-bold text-gray-900 text-lg">{{ $match['team2'] }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-gray-500">پیش‌بینی شما: <span class="font-bold text-gray-900">{{ $match['predicted'] }}</span></span>
                            @if($match['actual'])
                                <span class="text-gray-500">نتیجه واقعی: <span class="font-bold text-gray-900">{{ $match['actual'] }}</span></span>
                            @endif
                        </div>
                    </div>
                    <div class="text-left">
                        @if($match['status'] === 'finished')
                            <div class="text-2xl font-bold {{ $match['points'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $match['points'] > 0 ? '+' : '' }}{{ $match['points'] }}
                            </div>
                            <div class="text-xs {{ $match['points'] > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">امتیاز</div>
                        @elseif($match['status'] === 'upcoming')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">در انتظار</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="p-4 bg-gray-50 text-center">
            <a href="{{ route('matches') }}" class="text-green-700 hover:text-green-800 font-medium text-sm">
                مشاهده همه بازی‌ها →
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const ctx = document.getElementById('scoreChart').getContext('2d');
const scoreChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['هفته 1', 'هفته 2', 'هفته 3', 'هفته 4', 'هفته 5', 'هفته 6'],
        datasets: [{
            label: 'امتیاز کل',
            data: [35, 58, 72, 95, 112, 128],
            borderColor: 'rgb(22, 163, 74)',
            backgroundColor: 'rgba(22, 163, 74, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
@endsection
@section('scripts')
<script>
const ctx = document.getElementById('scoreChart').getContext('2d');
const scoreChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'امتیاز کل',
            data: {!! json_encode($chartData) !!},
            borderColor: 'rgb(22, 163, 74)',
            backgroundColor: 'rgba(22, 163, 74, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endsection