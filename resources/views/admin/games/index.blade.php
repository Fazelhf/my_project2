@extends('layouts.admin')

@section('title', 'مدیریت بازی‌ها')
@section('page-title', 'بازی‌ها')

@section('content')

@php
    $stageLabels = [
        'group'         => ['label' => 'مرحله گروهی',    'color' => '#4D9FFF'],
        'round_of_16'   => ['label' => 'جام شانزدهم',    'color' => '#A78BFA'],
        'quarter_final' => ['label' => 'ربع‌نهایی',      'color' => '#00e476'],
        'semi_final'    => ['label' => 'نیمه‌نهایی',     'color' => '#F59E0B'],
        'third_place'   => ['label' => 'رده‌بندی سوم',   'color' => '#FF8A8A'],
        'final'         => ['label' => 'فینال',           'color' => '#F59E0B'],
    ];
@endphp

<div class="flex items-center justify-between mb-5">
    <p class="text-sm font-mono" style="color:rgba(185,203,185,0.6);">{{ $games->flatten()->count() }} بازی</p>
    <a href="{{ route('admin.games.create') }}"
       class="px-5 py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all flex items-center gap-2"
       style="background:#00e476;color:#003919;"
       onmouseover="this.style.boxShadow='0 0 20px rgba(0,228,118,0.4)'"
       onmouseout="this.style.boxShadow=''">
        <span class="material-symbols-outlined text-base">add</span>
        بازی جدید
    </a>
</div>

@foreach($stageLabels as $stage => $info)
    @if($games->has($stage))
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-2.5 h-2.5 rounded-full" style="background:{{ $info['color'] }};box-shadow:0 0 6px {{ $info['color'] }};"></div>
                <h2 class="text-xs font-bold uppercase tracking-wider" style="color:{{ $info['color'] }};">{{ $info['label'] }}</h2>
            </div>
            <div class="liquid-glass rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.02);">
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">بازی</th>
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider hidden md:table-cell" style="color:rgba(185,203,185,0.6);">زمان</th>
                                <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">وضعیت</th>
                                <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games[$stage] as $game)
                                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);"
                                    onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                                    onmouseout="this.style.background=''">
                                    <td class="px-5 py-3">
                                        <span class="text-white font-medium">{{ $game->homeTeam->name }}</span>
                                        <span class="mx-1.5" style="color:rgba(255,255,255,0.3);">vs</span>
                                        <span class="text-white font-medium">{{ $game->awayTeam->name }}</span>
                                        @if($game->is_disciplinary)
                                            <span class="mr-2 text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(255,90,90,0.1);color:#FF8A8A;border:1px solid rgba(255,90,90,0.25);">انضباطی</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 hidden md:table-cell font-mono text-xs" style="color:rgba(185,203,185,0.6);">
                                        {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M Y H:i') }}
                                        <span class="block text-xs opacity-50">تهران</span>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($game->status === 'finished')
                                            <span class="font-black font-heading ml-2" style="color:#00e476;">{{ $game->home_score }}–{{ $game->away_score }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.25);">پایان</span>
                                        @elseif($game->status === 'live')
                                            <span class="text-xs px-2 py-0.5 rounded-full font-bold flex items-center gap-1 inline-flex" style="background:rgba(77,159,255,0.1);color:#4D9FFF;border:1px solid rgba(77,159,255,0.25);">
                                                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#4D9FFF;"></span>
                                                زنده
                                            </span>
                                        @else
                                            @if($game->scheduled_at && $game->scheduled_at->isPast())
                                                <span class="text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(245,158,11,0.1);color:#F59E0B;border:1px solid rgba(245,158,11,0.25);">در انتظار نتیجه</span>
                                            @else
                                                <span class="text-xs px-2 py-0.5 rounded-full font-bold" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">پیش‌رو</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-left">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('admin.games.show', $game) }}"
                                               class="text-xs font-bold transition-colors" style="color:#00e476;"
                                               onmouseover="this.style.color='#00ff85'" onmouseout="this.style.color='#00e476'">
                                                {{ $game->status === 'finished' ? 'جزئیات' : 'ثبت نتیجه' }}
                                            </a>
                                            @if($game->status !== 'finished')
                                                <a href="{{ route('admin.games.edit', $game) }}"
                                                   class="text-xs font-bold transition-colors" style="color:#4D9FFF;"
                                                   onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#4D9FFF'">
                                                    ویرایش
                                                </a>
                                                <form method="POST" action="{{ route('admin.games.destroy', $game) }}"
                                                      onsubmit="return confirm('حذف این بازی؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold cursor-pointer transition-colors" style="color:#FF8A8A;"
                                                            onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#FF8A8A'">
                                                        حذف
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endforeach

@if($games->isEmpty())
    <div class="liquid-glass rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-4xl mb-3 block" style="color:rgba(0,228,118,0.3);">sports_soccer</span>
        <p class="text-sm mb-4" style="color:rgba(185,203,185,0.5);">هیچ بازی‌ای ثبت نشده است.</p>
        <a href="{{ route('admin.games.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all"
           style="background:#00e476;color:#003919;">
            <span class="material-symbols-outlined text-base">add</span>
            بازی جدید
        </a>
    </div>
@endif

@endsection
