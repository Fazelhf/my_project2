@extends('layouts.app')

@section('title', 'پیش‌بینی کل جام')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">پیش‌بینی کل جام جهانی</h1>
        <p class="text-gray-600">پیش‌بینی صعودکنندگان، مراحل حذفی و قهرمان نهایی</p>
    </div>

    @if($userPrediction['is_locked'])
        <div class="bg-yellow-50 border-r-4 border-yellow-500 p-4 rounded-lg mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <div>
                    <p class="font-bold text-yellow-900">پیش‌بینی قفل شده است</p>
                    <p class="text-yellow-800 text-sm">پس از شروع جام، امکان تغییر پیش‌بینی وجود ندارد.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Group Stage Predictions -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
            <span class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </span>
            مرحله گروهی
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($groups as $groupName => $teams)
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-800 p-4">
                    <h3 class="text-2xl font-bold text-white text-center">گروه {{ $groupName }}</h3>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">🥇 تیم اول</label>
                        @if($userPrediction['is_locked'])
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl font-medium text-gray-900">
                                {{ $userPrediction['group_winners'][$groupName][0] }}
                            </div>
                        @else
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @foreach($teams as $team)
                                    <option {{ $userPrediction['group_winners'][$groupName][0] === $team ? 'selected' : '' }}>{{ $team }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">🥈 تیم دوم</label>
                        @if($userPrediction['is_locked'])
                            <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl font-medium text-gray-900">
                                {{ $userPrediction['group_winners'][$groupName][1] }}
                            </div>
                        @else
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @foreach($teams as $team)
                                    <option {{ $userPrediction['group_winners'][$groupName][1] === $team ? 'selected' : '' }}>{{ $team }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Knockout Stage -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Round of 16 -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                یک‌هشتم نهایی (8 تیم)
            </h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach($userPrediction['round_of_16'] as $index => $team)
                <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl font-medium text-gray-900 text-center">
                    {{ $team }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quarter Finals -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </span>
                یک‌چهارم نهایی (4 تیم)
            </h2>
            <div class="grid grid-cols-2 gap-3">
                @foreach($userPrediction['quarter_finals'] as $team)
                <div class="px-4 py-3 bg-purple-50 border border-purple-200 rounded-xl font-medium text-gray-900 text-center">
                    {{ $team }}
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Semi Finals and Finals -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Semi Finals -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </span>
                نیمه‌نهایی
            </h2>
            <div class="space-y-3">
                @foreach($userPrediction['semi_finals'] as $team)
                <div class="px-4 py-3 bg-orange-50 border border-orange-200 rounded-xl font-medium text-gray-900 text-center">
                    {{ $team }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Finals -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </span>
                فینال
            </h2>
            <div class="space-y-3">
                @foreach($userPrediction['final'] as $team)
                <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl font-medium text-gray-900 text-center">
                    {{ $team }}
                </div>
                @endforeach
            </div>
        </div>

        <!-- Champion -->
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl shadow-lg p-6 text-center">
            <h2 class="text-2xl font-bold text-yellow-900 mb-6">🏆 قهرمان</h2>
            <div class="text-6xl mb-4">🏆</div>
            <div class="text-3xl font-bold text-yellow-900 mb-2">
                {{ $userPrediction['champion'] }}
            </div>
            <div class="text-yellow-900 font-medium text-sm">
                پیش‌بینی قهرمان شما
            </div>
            @if(!$userPrediction['is_locked'])
            <p class="text-xs text-yellow-900 mt-4 bg-yellow-500/30 rounded-lg p-2">
                30 امتیاز برای پیش‌بینی درست!
            </p>
            @endif
        </div>
    </div>

    @if(!$userPrediction['is_locked'])
    <!-- Save Button -->
    <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form action="#" method="POST" onsubmit="event.preventDefault(); alert('ذخیره پیش‌بینی در Build Mode فعال می‌شود')">
            @csrf
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">آماده ذخیره پیش‌بینی خود هستید؟</h3>
                    <p class="text-gray-600 text-sm mt-1">توجه: پس از شروع جام، امکان تغییر پیش‌بینی وجود نخواهد داشت.</p>
                </div>
                <button type="submit" class="bg-gradient-to-r from-green-600 to-green-800 text-white px-8 py-3 rounded-xl font-bold hover:from-green-700 hover:to-green-900 transition-all transform hover:scale-105">
                    ذخیره پیش‌بینی
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection