@extends('layouts.app')

@section('title', 'جدول رده‌بندی')
@section('page-title', 'جدول رده‌بندی')

@section('content')

<div class="mb-5">
    <p class="text-sm text-brand-muted">رتبه‌بندی کارمندان بر اساس امتیاز کل پیش‌بینی‌ها</p>
</div>

<div class="rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-brand-border">
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">#</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">نام</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider hidden sm:table-cell">دپارتمان</th>
                    <th class="px-5 py-3.5 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">امتیاز</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-border">
                @forelse($users as $i => $u)
                    @php $isMe = $u->id === auth()->id(); @endphp
                    <tr @class([
                        'transition-colors duration-100 hover:bg-brand-card',
                        'bg-green-950/20' => $isMe,
                    ])>
                        <td class="px-5 py-3.5">
                            @if($i === 0)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 text-xs font-bold">1</span>
                            @elseif($i === 1)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-400/20 text-slate-300 text-xs font-bold">2</span>
                            @elseif($i === 2)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-orange-700/20 text-orange-400 text-xs font-bold">3</span>
                            @else
                                <span class="text-brand-subtle text-xs">{{ $i + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div @class([
                                    'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0',
                                    'bg-brand-green text-black' => $isMe,
                                    'bg-brand-card text-brand-muted' => !$isMe,
                                ])>
                                    {{ mb_strtoupper(mb_substr($u->name, 0, 1, 'UTF-8')) }}
                                </div>
                                <span @class([
                                    'font-medium',
                                    'text-brand-green' => $isMe,
                                    'text-brand-text'  => !$isMe,
                                ])>
                                    {{ $u->name }}
                                    @if($isMe)
                                        <span class="text-xs font-normal text-green-600 mr-1">(شما)</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 hidden sm:table-cell text-brand-muted">
                            {{ $u->department ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span @class([
                                'font-bold text-base',
                                'text-brand-green' => $i < 3,
                                'text-brand-text'  => $i >= 3,
                            ])>{{ $u->total_score }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-sm text-brand-subtle">
                            هنوز کاربری ثبت‌نام نکرده است.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
