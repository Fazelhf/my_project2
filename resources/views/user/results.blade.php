@extends('layouts.app')

@section('title', 'نتایج بازی‌ها')

@section('content')

<div class="mb-6 animate-slide-up">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-1 h-6 rounded-full" style="background:linear-gradient(180deg,#00E5A0,#4D9FFF);"></div>
        <h1 class="text-xl font-black font-heading text-brand-text">نتایج بازی‌ها</h1>
    </div>
    <p class="text-xs text-brand-muted mr-4">نتایج بازی‌های پایان‌یافته و پیش‌بینی‌های دیگران</p>
</div>

@forelse($games as $game)
@php
    $preds = $game->predictions->sortByDesc('points_earned');
    $myPred = $game->predictions->firstWhere('user_id', auth()->id());
@endphp
<div class="glass-card rounded-2xl mb-4 overflow-hidden bento-card" style="animation:slide-up .4s cubic-bezier(.16,1,.3,1) both;">

    {{-- Match header --}}
    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);">
        <div class="flex items-center justify-between gap-4">
            {{-- Home team --}}
            <div class="flex-1 text-center">
                <div class="w-12 h-12 rounded-xl mx-auto mb-2 flex items-center justify-center overflow-hidden"
                     style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
                    @if($game->homeTeam?->flag_url)
                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <span class="text-sm font-black font-heading hidden w-full h-full items-center justify-center" style="color:#F0F4FF;">{{ $game->homeTeam->code }}</span>
                    @else
                        <span class="text-sm font-black font-heading" style="color:#F0F4FF;">{{ $game->homeTeam?->code ?? '?' }}</span>
                    @endif
                </div>
                <p class="text-xs font-bold text-brand-text">{{ $game->homeTeam?->name_fa ?? $game->homeTeam?->name }}</p>
            </div>

            {{-- Score --}}
            <div class="text-center flex-shrink-0">
                <p class="text-3xl font-black font-heading" style="color:#00E5A0;">
                    {{ $game->home_score }} – {{ $game->away_score }}
                </p>
                <p class="text-[10px] text-brand-muted mt-1">{{ $game->scheduled_at?->format('j M Y') }}</p>
                @if($game->venue)
                    <p class="text-[10px] text-brand-subtle mt-0.5">{{ $game->venue }}</p>
                @endif
                <span class="badge badge-green text-[10px] mt-1">پایان یافت</span>
            </div>

            {{-- Away team --}}
            <div class="flex-1 text-center">
                <div class="w-12 h-12 rounded-xl mx-auto mb-2 flex items-center justify-center overflow-hidden"
                     style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
                    @if($game->awayTeam?->flag_url)
                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <span class="text-sm font-black font-heading hidden w-full h-full items-center justify-center" style="color:#F0F4FF;">{{ $game->awayTeam->code }}</span>
                    @else
                        <span class="text-sm font-black font-heading" style="color:#F0F4FF;">{{ $game->awayTeam?->code ?? '?' }}</span>
                    @endif
                </div>
                <p class="text-xs font-bold text-brand-text">{{ $game->awayTeam?->name_fa ?? $game->awayTeam?->name }}</p>
            </div>
        </div>

        {{-- My prediction highlight --}}
        @if($myPred)
        <div class="mt-3 rounded-xl px-4 py-2.5 flex items-center justify-between"
             style="background:rgba(0,229,160,0.07);border:1px solid rgba(0,229,160,0.2);">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-md flex items-center justify-center"
                     style="background:rgba(0,229,160,0.2);">
                    <svg class="w-3 h-3" style="color:#00E5A0;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold" style="color:#00E5A0;">پیش‌بینی من</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-black font-heading text-brand-text">{{ $myPred->home_score }}–{{ $myPred->away_score }}</span>
                @if($myPred->points_earned !== null)
                    @php $pc = $myPred->points_earned >= 10 ? 'badge-green' : ($myPred->points_earned >= 7 ? 'badge-blue' : ($myPred->points_earned >= 5 ? 'badge-gold' : ($myPred->points_earned >= 2 ? 'badge-gray' : 'badge-red'))); @endphp
                    <span class="badge {{ $pc }}">+{{ $myPred->points_earned }}</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Predictions list --}}
    @if($preds->isNotEmpty())
    <div class="px-5 py-3">
        <p class="text-[10px] font-bold text-brand-subtle mb-3 uppercase tracking-widest">پیش‌بینی‌های همه ({{ $preds->count() }} نفر)</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
            @foreach($preds as $pred)
            @php
                $isMe = $pred->user_id === auth()->id();
                $pts = $pred->points_earned;
                $color = $pts >= 10 ? '#00E5A0' : ($pts >= 7 ? '#4D9FFF' : ($pts >= 5 ? '#F5A623' : ($pts >= 2 ? '#8BA0C4' : '#FF5A5A')));
            @endphp
            <div class="rounded-xl px-3 py-2 flex items-center justify-between gap-2"
                 style="background:{{ $isMe ? 'rgba(0,229,160,0.06)' : 'rgba(255,255,255,0.03)' }};border:1px solid {{ $isMe ? 'rgba(0,229,160,0.2)' : 'rgba(255,255,255,0.07)' }};">
                <div class="flex items-center gap-1.5 min-w-0">
                    <div class="w-5 h-5 rounded-lg flex items-center justify-center text-[9px] font-black font-heading flex-shrink-0"
                         style="{{ $isMe ? 'background:linear-gradient(135deg,#00BF85,#00E5A0);color:#0a0a0a;' : 'background:rgba(255,255,255,0.08);color:#F0F4FF;' }}">
                        {{ mb_strtoupper(mb_substr($pred->user->name,0,1,'UTF-8')) }}
                    </div>
                    <span class="text-[10px] font-semibold truncate" style="{{ $isMe ? 'color:#00E5A0;' : 'color:#8BA0C4;' }}">
                        {{ $isMe ? 'من' : $pred->user->name }}
                    </span>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="text-xs font-black font-heading" style="color:{{ $color }};">{{ $pred->home_score }}–{{ $pred->away_score }}</span>
                    @if($pts !== null)
                        <div class="text-[9px] font-bold" style="color:{{ $color }};">+{{ $pts }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="px-5 py-4 text-center">
        <p class="text-xs text-brand-subtle">هیچ پیش‌بینی‌ای برای این بازی ثبت نشده</p>
    </div>
    @endif

</div>
@empty
<div class="glass-card rounded-2xl p-16 text-center">
    <p class="text-brand-muted text-sm">هنوز بازی پایان‌یافته‌ای وجود ندارد</p>
</div>
@endforelse

{{-- Pagination --}}
@if($games->hasPages())
<div class="mt-6 flex justify-center" style="animation:fade-in .4s ease both;">
    {{ $games->links() }}
</div>
@endif

@endsection
