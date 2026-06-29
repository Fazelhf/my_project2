@extends('layouts.admin')

@section('title', 'مدیریت تیم‌ها')
@section('page-title', 'تیم‌ها')

@section('content')

{{-- ── Stats Row ── --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @php
        $teamStatCards = [
            ['val' => $stats['without_flag'], 'label' => 'در انتظار بررسی',   'color' => '#F59E0B', 'icon' => 'pending'],
            ['val' => $stats['with_flag'],    'label' => 'تیم‌های تأیید شده', 'color' => '#00e476', 'icon' => 'verified'],
            ['val' => $stats['groups'],       'label' => 'گروه‌های فعال',      'color' => '#4D9FFF', 'icon' => 'grid_view'],
            ['val' => $stats['total'],        'label' => 'تعداد کل تیم‌ها',   'color' => '#A78BFA', 'icon' => 'flag'],
        ];
    @endphp
    @foreach($teamStatCards as $sc)
    <div class="liquid-glass rounded-2xl p-4 flex items-center gap-3"
         style="border-right:3px solid {{ $sc['color'] }}40;">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:{{ $sc['color'] }}15;">
            <span class="material-symbols-outlined text-base" style="color:{{ $sc['color'] }};">{{ $sc['icon'] }}</span>
        </div>
        <div>
            <p class="text-xs" style="color:rgba(185,203,185,0.6);">{{ $sc['label'] }}</p>
            <p class="text-2xl font-black font-heading" style="color:{{ $sc['color'] }};">{{ $sc['val'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="flex items-center justify-between mb-5">
    <p class="text-sm font-mono" style="color:rgba(185,203,185,0.6);">{{ $teams->flatten()->count() }} تیم ثبت شده</p>
</div>

@foreach($teams->sortKeys() as $group => $groupTeams)
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-2.5 h-2.5 rounded-full" style="background:#00e476;box-shadow:0 0 6px #00e476;"></div>
            <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#00e476;">
                @if($group) گروه {{ $group }} @else بدون گروه @endif
            </h2>
        </div>
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.02);">
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">تیم</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider hidden sm:table-cell" style="color:rgba(185,203,185,0.6);">نام فارسی</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">کد FIFA</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider hidden md:table-cell" style="color:rgba(185,203,185,0.6);">وضعیت</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupTeams as $team)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);"
                            onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                            onmouseout="this.style.background=''">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if($team->flag_url)
                                        <img src="{{ $team->flag_url }}" alt="{{ $team->code }}"
                                             class="w-7 h-5 object-cover rounded flex-shrink-0"
                                             style="border:1px solid rgba(255,255,255,0.1);"
                                             onerror="this.style.display='none'">
                                    @else
                                        <div class="w-7 h-5 rounded flex-shrink-0 flex items-center justify-center"
                                             style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);">
                                            <span class="material-symbols-outlined" style="font-size:12px;color:rgba(185,203,185,0.3);">flag</span>
                                        </div>
                                    @endif
                                    <span class="font-medium text-white">{{ $team->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden sm:table-cell" style="color:rgba(185,203,185,0.7);">{{ $team->name_fa }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-md text-xs font-mono font-bold"
                                      style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">
                                    {{ $team->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell">
                                @if($team->flag_url)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold"
                                          style="background:rgba(0,228,118,0.12);color:#00e476;">تأیید شده</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold"
                                          style="background:rgba(245,158,11,0.12);color:#F59E0B;">در انتظار</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-left">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="text-xs font-bold transition-colors" style="color:#4D9FFF;"
                                       onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#4D9FFF'">
                                        ویرایش
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
                                          onsubmit="return confirm('آیا از حذف این تیم مطمئن هستید؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-bold cursor-pointer transition-colors" style="color:#FF8A8A;"
                                                onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#FF8A8A'">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

@if($teams->isEmpty())
    <div class="liquid-glass rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-4xl mb-3 block" style="color:rgba(0,228,118,0.3);">flag</span>
        <p class="text-sm" style="color:rgba(185,203,185,0.5);">هیچ تیمی ثبت نشده است.</p>
    </div>
@endif

@endsection
