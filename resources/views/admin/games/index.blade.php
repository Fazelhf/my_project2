@extends('layouts.admin')

@section('title', 'مدیریت بازی‌ها')
@section('page-title', 'بازی‌ها')

@section('content')

@php
    $stageLabels = [
        'group'         => 'مرحله گروهی',
        'round_of_16'   => 'جام شانزدهم',
        'quarter_final' => 'ربع‌نهایی',
        'semi_final'    => 'نیمه‌نهایی',
        'third_place'   => 'رده‌بندی سوم',
        'final'         => 'فینال',
    ];
@endphp

<div class="flex items-center justify-between mb-5">
    <p class="text-sm" style="color:#94A3B8;">{{ $games->flatten()->count() }} بازی</p>
    <a href="{{ route('admin.games.create') }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold cursor-pointer transition-colors"
       style="background-color:#22C55E; color:#020617;"
       onmouseover="this.style.backgroundColor='#16A34A';"
       onmouseout="this.style.backgroundColor='#22C55E';">
        + بازی جدید
    </a>
</div>

@foreach($stageLabels as $stage => $label)
    @if($games->has($stage))
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:#94A3B8;">{{ $label }}</h2>
            <div class="rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom:1px solid #334155;">
                                <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium" style="color:#475569;">بازی</th>
                                <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium hidden md:table-cell" style="color:#475569;">زمان</th>
                                <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium" style="color:#475569;">نتیجه / وضعیت</th>
                                <th class="px-5 py-3 text-left text-xs uppercase tracking-wider font-medium" style="color:#475569;">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:#334155;">
                            @foreach($games[$stage] as $game)
                                <tr onmouseover="this.style.backgroundColor='#1E293B';" onmouseout="this.style.backgroundColor='';">
                                    <td class="px-5 py-3">
                                        <span style="color:#F8FAFC;">{{ $game->homeTeam->name }}</span>
                                        <span class="mx-1.5" style="color:#475569;">vs</span>
                                        <span style="color:#F8FAFC;">{{ $game->awayTeam->name }}</span>
                                        @if($game->is_disciplinary)
                                            <span class="mr-1 text-xs px-1.5 py-0.5 rounded-md" style="background-color:#450a0a; color:#fca5a5;">انضباطی</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 hidden md:table-cell" style="color:#94A3B8;">
                                        {{ $game->scheduled_at?->format('j M Y H:i') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($game->status === 'finished')
                                            <span class="font-bold ml-2" style="color:#22C55E;">{{ $game->home_score }}–{{ $game->away_score }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-md" style="background-color:#14532d; color:#86efac;">پایان</span>
                                        @elseif($game->status === 'live')
                                            <span class="text-xs px-2 py-0.5 rounded-md" style="background-color:#1e3a5f; color:#93c5fd;">زنده</span>
                                        @else
                                            <span class="text-xs px-2 py-0.5 rounded-md" style="background-color:#1E293B; color:#94A3B8;">پیش‌رو</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-left">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.games.show', $game) }}"
                                               class="text-xs font-medium" style="color:#22C55E;"
                                               onmouseover="this.style.color='#86efac';" onmouseout="this.style.color='#22C55E';">
                                                {{ $game->status === 'finished' ? 'جزئیات' : 'ثبت نتیجه' }}
                                            </a>
                                            @if($game->status !== 'finished')
                                                <a href="{{ route('admin.games.edit', $game) }}"
                                                   class="text-xs font-medium" style="color:#60a5fa;"
                                                   onmouseover="this.style.color='#93c5fd';" onmouseout="this.style.color='#60a5fa';">
                                                    ویرایش
                                                </a>
                                                <form method="POST" action="{{ route('admin.games.destroy', $game) }}"
                                                      onsubmit="return confirm('حذف این بازی؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-medium cursor-pointer" style="color:#f87171;"
                                                            onmouseover="this.style.color='#fca5a5';" onmouseout="this.style.color='#f87171';">حذف</button>
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
    <div class="rounded-2xl border p-12 text-center" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-sm mb-3" style="color:#475569;">هیچ بازی‌ای ثبت نشده است.</p>
        <a href="{{ route('admin.games.create') }}"
           class="inline-block px-4 py-2 rounded-xl text-sm font-semibold"
           style="background-color:#22C55E; color:#020617;">+ بازی جدید</a>
    </div>
@endif

@endsection
