@extends('layouts.app')

@section('title', 'داشبورد')
@section('page-title', 'داشبورد')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO BANNER
═══════════════════════════════════════════════════════ --}}
<div class="relative rounded-2xl overflow-hidden mb-6 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_both]"
     style="background: linear-gradient(135deg, #0d1a00 0%, #0a1520 40%, #030810 100%); border: 1px solid rgba(245,158,11,0.2); min-height: 160px;">

    {{-- Background glow --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-30"
             style="background: radial-gradient(circle, rgba(245,158,11,0.3) 0%, transparent 70%); transform: translate(30%, -40%);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-20"
             style="background: radial-gradient(circle, rgba(16,185,129,0.4) 0%, transparent 70%); transform: translate(-20%, 30%);"></div>
    </div>

    {{-- World Cup badge top-right --}}
    <div class="absolute top-4 left-4 flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"
         style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.35); color: #FCD34D;">
        <span class="w-2 h-2 rounded-full animate-pulse" style="background: #F59E0B;"></span>
        FIFA World Cup 2026
    </div>

    <div class="relative px-6 py-8 flex items-center justify-between gap-6">
        <div class="flex-1 min-w-0">
            <p class="text-brand-muted text-sm mb-1">خوش آمدی،</p>
            <h2 class="text-2xl sm:text-3xl font-black font-heading leading-tight text-brand-text mb-3">
                {{ auth()->user()->name }}
            </h2>
            <p class="text-sm text-brand-muted max-w-sm">
                پیش‌بینی‌هایت رو ثبت کن و در جدول رده‌بندی شرکت صعود کن.
            </p>
            <div class="flex items-center gap-3 mt-4 flex-wrap">
                <a href="{{ route('games.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black font-heading cursor-pointer transition-all duration-200"
                   style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 25px rgba(245,158,11,0.3);"
                   onmouseover="this.style.boxShadow='0 0 40px rgba(245,158,11,0.5)'; this.style.transform='translateY(-1px)'"
                   onmouseout="this.style.boxShadow='0 0 25px rgba(245,158,11,0.3)'; this.style.transform='translateY(0)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    پیش‌بینی بازی‌ها
                </a>
                <a href="{{ route('leaderboard') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-all duration-200"
                   style="background: rgba(255,255,255,0.06); border: 1px solid #1E2D45; color: #8AAABB;"
                   onmouseover="this.style.borderColor='#2D4060'; this.style.color='#F1F5F9'"
                   onmouseout="this.style.borderColor='#1E2D45'; this.style.color='#8AAABB'">
                    جدول رده‌بندی
                </a>
            </div>
        </div>

        {{-- Trophy Stat --}}
        <div class="hidden md:flex flex-col items-center justify-center flex-shrink-0 w-28 h-28 rounded-2xl"
             style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);">
            <p class="text-4xl font-black font-heading gradient-text-gold leading-none">{{ auth()->user()->total_score }}</p>
            <p class="text-xs text-brand-muted mt-1.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="#F59E0B" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                امتیاز کل
            </p>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     STATS GRID
═══════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">

    {{-- Score --}}
    <div class="rounded-2xl p-5 transition-all duration-300 cursor-default animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.05s_both] group"
         style="background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(245,158,11,0.2);"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(245,158,11,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background: rgba(245,158,11,0.15);">
            <svg class="w-5 h-5" fill="#F59E0B" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        </div>
        <p class="text-3xl font-black font-heading gradient-text-gold leading-none animate-count">{{ auth()->user()->total_score }}</p>
        <p class="text-xs text-brand-muted mt-2 font-semibold uppercase tracking-wider">امتیاز کل</p>
    </div>

    {{-- Rank --}}
    <div class="rounded-2xl p-5 transition-all duration-300 cursor-default animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.1s_both]"
         style="background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(59,130,246,0.2);"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(59,130,246,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background: rgba(59,130,246,0.15);">
            <svg class="w-5 h-5" style="color: #3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <p class="text-3xl font-black font-heading leading-none animate-count" style="color: #93C5FD;">#{{ $rank }}</p>
        <p class="text-xs text-brand-muted mt-2 font-semibold uppercase tracking-wider">از {{ $totalUsers }} نفر</p>
    </div>

    {{-- Predictions --}}
    <div class="rounded-2xl p-5 transition-all duration-300 cursor-default animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.15s_both]"
         style="background: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(16,185,129,0.2);"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(16,185,129,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background: rgba(16,185,129,0.15);">
            <svg class="w-5 h-5" style="color: #10B981;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-3xl font-black font-heading leading-none animate-count" style="color: #6EE7B7;">{{ $predictionsMade }}</p>
        <p class="text-xs text-brand-muted mt-2 font-semibold uppercase tracking-wider">{{ $exactPredictions }} دقیق</p>
    </div>

    {{-- Accuracy --}}
    <div class="rounded-2xl p-5 transition-all duration-300 cursor-default animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.2s_both]"
         style="background: linear-gradient(135deg, rgba(139,92,246,0.08) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(139,92,246,0.2);"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(139,92,246,0.1)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mb-3" style="background: rgba(139,92,246,0.15);">
            <svg class="w-5 h-5" style="color: #8B5CF6;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-3xl font-black font-heading leading-none animate-count" style="color: #C4B5FD;">{{ $accuracy }}%</p>
        <p class="text-xs text-brand-muted mt-2 font-semibold uppercase tracking-wider">دقت پیش‌بینی</p>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     RECENT PREDICTIONS + SCORING GUIDE
═══════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Recent Predictions --}}
    <div class="lg:col-span-2 rounded-2xl overflow-hidden animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.25s_both]"
         style="background: #0d1525; border: 1px solid #1E2D45;">

        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom: 1px solid #1E2D45;">
            <div class="flex items-center gap-2">
                <div class="w-1 h-5 rounded-full" style="background: linear-gradient(180deg, #F59E0B, #10B981);"></div>
                <h3 class="font-black text-sm font-heading text-brand-text tracking-wide">آخرین پیش‌بینی‌ها</h3>
            </div>
            <a href="{{ route('games.index') }}"
               class="text-xs font-bold transition-colors duration-150 flex items-center gap-1"
               style="color: #F59E0B;"
               onmouseover="this.style.color='#FCD34D'"
               onmouseout="this.style.color='#F59E0B'">
                مشاهده همه
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

        @if($recentPredictions->isEmpty())
            <div class="px-5 py-16 text-center">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                     style="background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15);">
                    <svg class="w-8 h-8" style="color: #F59E0B;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-sm text-brand-muted mb-5">هنوز پیش‌بینی‌ای ثبت نشده</p>
                <a href="{{ route('games.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black font-heading cursor-pointer transition-all duration-200"
                   style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 20px rgba(245,158,11,0.25);">
                    شروع پیش‌بینی
                </a>
            </div>
        @else
            <div>
                @foreach($recentPredictions as $idx => $pred)
                    <div class="px-5 py-4 flex items-center justify-between gap-4 transition-all duration-150"
                         style="border-bottom: 1px solid #1a2234;"
                         onmouseover="this.style.background='rgba(245,158,11,0.03)'"
                         onmouseout="this.style.background='transparent'">
                        <div class="flex-1 min-w-0">
                            {{-- Match names --}}
                            <p class="text-sm font-bold text-brand-text truncate">
                                {{ $pred->game->homeTeam->name }}
                                <span class="text-brand-subtle font-normal mx-1">vs</span>
                                {{ $pred->game->awayTeam->name }}
                            </p>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium score-gray">
                                    پیش: {{ $pred->home_score }}–{{ $pred->away_score }}
                                </span>
                                @if($pred->game->status === 'finished')
                                    <span class="text-xs px-2 py-0.5 rounded-md font-medium score-blue">
                                        نتیجه: {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            @if($pred->points_earned !== null)
                                @php
                                    $pClass = $pred->points_earned >= 7 ? 'score-green'
                                        : ($pred->points_earned >= 5 ? 'score-blue'
                                        : ($pred->points_earned >= 2 ? 'score-gray' : 'score-red'));
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg text-sm font-black font-heading {{ $pClass }}">
                                    +{{ $pred->points_earned }}
                                </span>
                            @else
                                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold score-gray">در انتظار</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Scoring Guide --}}
    <div class="rounded-2xl p-5 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.3s_both]"
         style="background: #0d1525; border: 1px solid #1E2D45;">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-1 h-5 rounded-full" style="background: linear-gradient(180deg, #F59E0B, #10B981);"></div>
            <h3 class="font-black text-sm font-heading text-brand-text tracking-wide">سیستم امتیازدهی</h3>
        </div>

        <div class="space-y-2.5">
            @foreach([
                [10, 'نتیجه دقیق', 'score-green', 'تک'],
                [7,  'تفاضل گل یکسان', 'score-blue', 'دو'],
                [5,  'برنده درست',  'score-gray', 'سه'],
                [2,  'شرکت در پیش‌بینی', 'score-gray', 'چهار'],
            ] as [$pts, $label, $cls, $num])
                <div class="flex items-center gap-3 p-3 rounded-xl transition-all duration-150 cursor-default"
                     style="background: rgba(255,255,255,0.02); border: 1px solid transparent;"
                     onmouseover="this.style.borderColor='#1E2D45'; this.style.background='rgba(255,255,255,0.04)'"
                     onmouseout="this.style.borderColor='transparent'; this.style.background='rgba(255,255,255,0.02)'">
                    <span class="w-11 h-11 rounded-xl flex items-center justify-center text-base font-black font-heading flex-shrink-0 {{ $cls }}">
                        {{ $pts }}
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-brand-text">{{ $label }}</p>
                        <p class="text-xs text-brand-subtle">امتیاز</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 pt-4" style="border-top: 1px solid #1E2D45;">
            <a href="{{ route('games.index') }}"
               class="flex items-center justify-center gap-2 w-full py-3 rounded-xl text-sm font-black font-heading cursor-pointer transition-all duration-200"
               style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 20px rgba(245,158,11,0.25);"
               onmouseover="this.style.boxShadow='0 0 40px rgba(245,158,11,0.4)'; this.style.transform='translateY(-1px)'"
               onmouseout="this.style.boxShadow='0 0 20px rgba(245,158,11,0.25)'; this.style.transform='translateY(0)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                ثبت پیش‌بینی
            </a>
        </div>
    </div>

</div>

@endsection
