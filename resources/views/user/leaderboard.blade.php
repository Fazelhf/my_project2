@extends('layouts.app')

@section('title', 'جدول رده‌بندی')
@section('page-title', 'جدول رده‌بندی')

@section('content')

<div class="mb-6">
    <p class="text-sm" style="color:#94A3B8;">رتبه‌بندی کارمندان بر اساس امتیاز کل پیش‌بینی‌ها</p>
</div>

<div class="rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="border-bottom:1px solid #334155;">
                    <th class="px-5 py-3.5 text-right font-medium text-xs uppercase tracking-wider" style="color:#475569;">#</th>
                    <th class="px-5 py-3.5 text-right font-medium text-xs uppercase tracking-wider" style="color:#475569;">نام</th>
                    <th class="px-5 py-3.5 text-right font-medium text-xs uppercase tracking-wider hidden sm:table-cell" style="color:#475569;">دپارتمان</th>
                    <th class="px-5 py-3.5 text-right font-medium text-xs uppercase tracking-wider" style="color:#475569;">امتیاز</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:#334155;">
                @forelse($users as $i => $u)
                    @php $isMe = $u->id === auth()->id(); @endphp
                    <tr style="{{ $isMe ? 'background-color:#0d2818;' : '' }}"
                        onmouseover="this.style.backgroundColor='#1E293B';"
                        onmouseout="this.style.backgroundColor='{{ $isMe ? '#0d2818' : '' }}';">
                        <td class="px-5 py-3.5">
                            @if($i === 0)
                                <span class="text-base">🥇</span>
                            @elseif($i === 1)
                                <span class="text-base">🥈</span>
                            @elseif($i === 2)
                                <span class="text-base">🥉</span>
                            @else
                                <span style="color:#475569;">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     style="background-color:{{ $isMe ? '#22C55E' : '#1E293B' }}; color:{{ $isMe ? '#020617' : '#94A3B8' }};">
                                    {{ mb_strtoupper(mb_substr($u->name, 0, 2, 'UTF-8')) }}
                                </div>
                                <span class="font-medium" style="color:{{ $isMe ? '#22C55E' : '#F8FAFC' }};">
                                    {{ $u->name }}
                                    @if($isMe)
                                        <span class="text-xs font-normal mr-1" style="color:#16A34A;">(شما)</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell" style="color:#94A3B8;">
                            {{ $u->department ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-base" style="color:{{ $i < 3 ? '#22C55E' : '#F8FAFC' }};">
                                {{ $u->total_score }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-sm" style="color:#475569;">
                            هنوز کاربری ثبت‌نام نکرده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
