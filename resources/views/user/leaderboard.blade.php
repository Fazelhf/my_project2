@extends('layouts.app')

@section('title', 'جدول رده‌بندی')
@section('page-title', 'جدول رده‌بندی')

@section('content')

{{-- Header --}}
<div class="mb-6 animate-[slide-up_0.4s_cubic-bezier(0.16,1,0.3,1)_both]">
    <p class="text-sm text-brand-muted">رتبه‌بندی کارمندان بر اساس امتیاز کل پیش‌بینی‌ها</p>
</div>

{{-- Top 3 Podium --}}
@if($users->count() >= 3)
<div class="grid grid-cols-3 gap-3 mb-6 animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.05s_both]">

    {{-- 2nd place --}}
    <div class="rounded-2xl p-4 text-center transition-all duration-300 mt-8 cursor-default"
         style="background: linear-gradient(180deg, rgba(148,163,184,0.06) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(148,163,184,0.25);"
         onmouseover="this.style.transform='translateY(-4px)'"
         onmouseout="this.style.transform='translateY(0)'">
        <div class="w-12 h-12 rounded-2xl mx-auto mb-3 flex items-center justify-center text-lg font-black font-heading"
             style="background: linear-gradient(135deg, #64748B, #94A3B8); color: #0a0a0a; box-shadow: 0 0 20px rgba(148,163,184,0.2);">
            {{ mb_strtoupper(mb_substr($users[1]->name, 0, 1, 'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background: rgba(148,163,184,0.15); border: 1px solid rgba(148,163,184,0.3); color: #CBD5E1;">
            2
        </div>
        <p class="text-sm font-bold text-brand-text truncate">{{ $users[1]->name }}</p>
        <p class="text-xs text-brand-muted mt-0.5 truncate">{{ $users[1]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color: #94A3B8;">{{ $users[1]->total_score }}</p>
    </div>

    {{-- 1st place --}}
    <div class="rounded-2xl p-4 text-center transition-all duration-300 cursor-default relative overflow-hidden"
         style="background: linear-gradient(180deg, rgba(245,158,11,0.12) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(245,158,11,0.4);"
         onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 20px 40px rgba(245,158,11,0.15)'"
         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
        {{-- Crown glow --}}
        <div class="absolute top-0 inset-x-0 h-24 pointer-events-none"
             style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(245,158,11,0.2), transparent);"></div>

        {{-- Crown icon --}}
        <div class="relative mb-2">
            <svg class="w-7 h-7 mx-auto" viewBox="0 0 24 24" fill="#F59E0B">
                <path d="M2 19h20v2H2v-2zm18-8l-3 3-5-5-5 5-3-3V5l3 3 5-5 5 5 3-3v6z"/>
            </svg>
        </div>

        <div class="w-14 h-14 rounded-2xl mx-auto mb-3 flex items-center justify-center text-xl font-black font-heading relative"
             style="background: linear-gradient(135deg, #92400E, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 30px rgba(245,158,11,0.4);">
            {{ mb_strtoupper(mb_substr($users[0]->name, 0, 1, 'UTF-8')) }}
        </div>
        <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-black"
             style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 15px rgba(245,158,11,0.4);">
            1
        </div>
        <p class="text-sm font-black text-brand-text truncate">{{ $users[0]->name }}</p>
        <p class="text-xs text-brand-muted mt-0.5 truncate">{{ $users[0]->department ?: '—' }}</p>
        <p class="text-3xl font-black font-heading mt-2 gradient-text-gold">{{ $users[0]->total_score }}</p>
    </div>

    {{-- 3rd place --}}
    <div class="rounded-2xl p-4 text-center transition-all duration-300 mt-12 cursor-default"
         style="background: linear-gradient(180deg, rgba(194,65,12,0.06) 0%, rgba(10,15,30,0.9) 100%); border: 1px solid rgba(194,65,12,0.25);"
         onmouseover="this.style.transform='translateY(-4px)'"
         onmouseout="this.style.transform='translateY(0)'">
        <div class="w-12 h-12 rounded-2xl mx-auto mb-3 flex items-center justify-center text-lg font-black font-heading"
             style="background: linear-gradient(135deg, #7C2D12, #C2410C); color: #FED7AA; box-shadow: 0 0 20px rgba(194,65,12,0.2);">
            {{ mb_strtoupper(mb_substr($users[2]->name, 0, 1, 'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background: rgba(194,65,12,0.15); border: 1px solid rgba(194,65,12,0.3); color: #FDBA74;">
            3
        </div>
        <p class="text-sm font-bold text-brand-text truncate">{{ $users[2]->name }}</p>
        <p class="text-xs text-brand-muted mt-0.5 truncate">{{ $users[2]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color: #FB923C;">{{ $users[2]->total_score }}</p>
    </div>
</div>
@endif

{{-- Full Leaderboard Table --}}
<div class="rounded-2xl overflow-hidden animate-[slide-up_0.5s_cubic-bezier(0.16,1,0.3,1)_0.15s_both]"
     style="background: #0d1525; border: 1px solid #1E2D45;">

    <div class="px-5 py-4 flex items-center gap-2" style="border-bottom: 1px solid #1E2D45;">
        <div class="w-1 h-5 rounded-full" style="background: linear-gradient(180deg, #F59E0B, #10B981);"></div>
        <h3 class="font-black text-sm font-heading text-brand-text tracking-wide">همه رتبه‌بندی‌ها</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom: 1px solid #1E2D45; background: rgba(255,255,255,0.02);">
                    <th class="px-5 py-3 text-right text-xs font-bold text-brand-subtle uppercase tracking-widest w-14">#</th>
                    <th class="px-5 py-3 text-right text-xs font-bold text-brand-subtle uppercase tracking-widest">نام</th>
                    <th class="px-5 py-3 text-right text-xs font-bold text-brand-subtle uppercase tracking-widest hidden sm:table-cell">دپارتمان</th>
                    <th class="px-5 py-3 text-right text-xs font-bold text-brand-subtle uppercase tracking-widest">امتیاز</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                    @php $isMe = $u->id === auth()->id(); @endphp
                    <tr class="transition-all duration-150"
                        style="{{ $isMe ? 'background: rgba(16,185,129,0.05); border-right: 3px solid #10B981;' : '' }}"
                        onmouseover="this.style.background='rgba(255,255,255,0.025)'"
                        onmouseout="this.style.background='{{ $isMe ? 'rgba(16,185,129,0.05)' : 'transparent' }}'">

                        <td class="px-5 py-3.5" style="border-bottom: 1px solid #131e30;">
                            @if($i === 0)
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                                     style="background: linear-gradient(135deg, #D97706, #F59E0B); color: #0a0a0a; box-shadow: 0 0 12px rgba(245,158,11,0.3);">1</div>
                            @elseif($i === 1)
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                                     style="background: linear-gradient(135deg, #64748B, #94A3B8); color: #0a0a0a;">2</div>
                            @elseif($i === 2)
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                                     style="background: linear-gradient(135deg, #7C2D12, #C2410C); color: #FED7AA;">3</div>
                            @else
                                <span class="text-brand-subtle text-xs font-semibold">{{ $i + 1 }}</span>
                            @endif
                        </td>

                        <td class="px-5 py-3.5" style="border-bottom: 1px solid #131e30;">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                                     style="{{ $isMe
                                         ? 'background: linear-gradient(135deg, #059669, #10B981); color: #0a0a0a;'
                                         : 'background: rgba(255,255,255,0.06); border: 1px solid #1E2D45; color: #F1F5F9;' }}">
                                    {{ mb_strtoupper(mb_substr($u->name, 0, 1, 'UTF-8')) }}
                                </div>
                                <div>
                                    <span class="font-bold {{ $isMe ? '' : 'text-brand-text' }}"
                                          style="{{ $isMe ? 'color: #6EE7B7;' : '' }}">
                                        {{ $u->name }}
                                    </span>
                                    @if($isMe)
                                        <span class="text-xs font-semibold mr-1 px-1.5 py-0.5 rounded-md score-green text-[11px]">شما</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-3.5 text-brand-muted text-sm hidden sm:table-cell"
                            style="border-bottom: 1px solid #131e30;">
                            {{ $u->department ?: '—' }}
                        </td>

                        <td class="px-5 py-3.5" style="border-bottom: 1px solid #131e30;">
                            @if($i === 0)
                                <span class="text-xl font-black font-heading gradient-text-gold">{{ $u->total_score }}</span>
                            @elseif($i < 3)
                                <span class="text-lg font-black font-heading" style="color: #94A3B8;">{{ $u->total_score }}</span>
                            @else
                                <span class="font-bold text-brand-text">{{ $u->total_score }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-16 text-center text-sm text-brand-subtle">
                            هنوز کاربری ثبت‌نام نکرده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
