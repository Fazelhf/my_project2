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
    <p class="text-sm text-brand-muted">{{ $games->flatten()->count() }} بازی</p>
    <a href="{{ route('admin.games.create') }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold cursor-pointer transition-colors
              bg-brand-green hover:bg-brand-green-dim text-black">
        + بازی جدید
    </a>
</div>

@foreach($stageLabels as $stage => $label)
    @if($games->has($stage))
        <div class="mb-6">
            <h2 class="text-xs font-bold uppercase tracking-wider text-brand-muted mb-3">{{ $label }}</h2>
            <div class="rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-brand-border">
                                <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">بازی</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider hidden md:table-cell">زمان</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">نتیجه / وضعیت</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-brand-subtle uppercase tracking-wider">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border">
                            @foreach($games[$stage] as $game)
                                <tr class="hover:bg-brand-card transition-colors duration-100">
                                    <td class="px-5 py-3">
                                        <span class="text-brand-text">{{ $game->homeTeam->name }}</span>
                                        <span class="mx-1.5 text-brand-subtle">vs</span>
                                        <span class="text-brand-text">{{ $game->awayTeam->name }}</span>
                                        @if($game->is_disciplinary)
                                            <span class="mr-1 text-xs px-1.5 py-0.5 rounded-md bg-red-950/50 text-red-300">انضباطی</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 hidden md:table-cell text-brand-muted">
                                        {{ $game->scheduled_at?->format('j M Y H:i') }}
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($game->status === 'finished')
                                            <span class="font-bold text-brand-green ml-2">{{ $game->home_score }}–{{ $game->away_score }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-green-950/50 text-green-300">پایان</span>
                                        @elseif($game->status === 'live')
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-blue-950/50 text-blue-300">زنده</span>
                                        @else
                                            <span class="text-xs px-2 py-0.5 rounded-md bg-brand-card text-brand-muted">پیش‌رو</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-left">
                                        <div class="flex items-center justify-end gap-4">
                                            <a href="{{ route('admin.games.show', $game) }}"
                                               class="text-xs font-medium text-brand-green hover:text-green-400 transition-colors">
                                                {{ $game->status === 'finished' ? 'جزئیات' : 'ثبت نتیجه' }}
                                            </a>
                                            @if($game->status !== 'finished')
                                                <a href="{{ route('admin.games.edit', $game) }}"
                                                   class="text-xs font-medium text-brand-blue hover:text-blue-300 transition-colors">
                                                    ویرایش
                                                </a>
                                                <form method="POST" action="{{ route('admin.games.destroy', $game) }}"
                                                      onsubmit="return confirm('حذف این بازی؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-medium cursor-pointer text-brand-red hover:text-red-300 transition-colors">
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
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-12 text-center">
        <p class="text-sm text-brand-subtle mb-3">هیچ بازی‌ای ثبت نشده است.</p>
        <a href="{{ route('admin.games.create') }}"
           class="inline-block px-4 py-2 rounded-xl text-sm font-semibold bg-brand-green hover:bg-brand-green-dim text-black transition-colors">
            + بازی جدید
        </a>
    </div>
@endif

@endsection
