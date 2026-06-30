@extends('layouts.app')

@section('title', 'داشبورد')

@section('content')

@php
    $user = auth()->user();
    $myPreds = $predictions ?? collect();
    $upcomingGames = $upcomingGames ?? collect();
    $rank = $rank ?? '—';
    $totalPreds = $totalPredictions ?? 0;
    $correctPreds = $correctPredictions ?? 0;
    $exactPreds = $exactPredictions ?? 0;
@endphp

{{-- ── Hero Stats Row ──────────────────────────────────────── --}}
<section class="grid grid-cols-1 md:grid-cols-12 gap-5 mb-6">

    {{-- امتیاز کل --}}
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-8 flex flex-col justify-between h-[200px] reveal animate-slide-up stagger-1">
        <div>
            <p class="text-sm mb-1" style="color:rgba(185,203,185,0.6);">امتیاز کل</p>
            <h2 class="text-5xl font-black font-heading" style="color:#00e476;">{{ $user->total_score ?? 0 }}</h2>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-2 text-sm font-bold" style="color:#00e476;">
                <span class="material-symbols-outlined text-base">trending_up</span>
                <span>خوش آمدی، {{ $user->name }}</span>
            </div>
            <div class="w-full h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.1);">
                <div class="h-full rounded-full shadow-[0_0_10px_#00e476]" style="width:{{ min(($user->total_score ?? 0) / 20, 100) }}%;background:#00e476;"></div>
            </div>
        </div>
    </div>

    {{-- رتبه در لیگ --}}
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-8 flex flex-col justify-between h-[200px] reveal animate-slide-up stagger-2" style="border-right:4px solid #4D9FFF;background:linear-gradient(135deg,rgba(77,159,255,0.05),rgba(14,20,29,0.8));">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm mb-1" style="color:rgba(185,203,185,0.6);">رتبه در لیگ</p>
                <h2 class="text-5xl font-black font-heading" style="color:#4D9FFF;">#{{ $rank }}</h2>
            </div>
            <div class="p-3 rounded-2xl" style="background:rgba(77,159,255,0.1);">
                <span class="material-symbols-outlined text-3xl" style="color:#4D9FFF;">leaderboard</span>
            </div>
        </div>
        <div>
            <p class="text-xs mb-2" style="color:rgba(185,203,185,0.4);">جایگاه واقعی در جدول رده‌بندی</p>
            <a href="{{ route('leaderboard') }}" class="text-xs font-bold flex items-center gap-1" style="color:#4D9FFF;">
                <span class="material-symbols-outlined text-sm">open_in_new</span>مشاهده جدول
            </a>
        </div>
    </div>

    {{-- دقت پیش‌بینی --}}
    @php
        $accuracy = $totalPreds > 0 ? round(($correctPreds / $totalPreds) * 100) : 0;
        $exactRate = $totalPreds > 0 ? round(($exactPreds / $totalPreds) * 100) : 0;
    @endphp
    <div class="md:col-span-4 liquid-glass bento-card rounded-3xl p-6 flex flex-col justify-between h-[200px] reveal animate-slide-up stagger-3" style="border-right:4px solid #F5A623;background:linear-gradient(135deg,rgba(245,166,35,0.05),rgba(14,20,29,0.8));">
        <div class="flex justify-between items-start">
            <p class="text-sm" style="color:rgba(185,203,185,0.6);">دقت پیش‌بینی</p>
            <span class="material-symbols-outlined text-2xl" style="color:#F5A623;">target</span>
        </div>
        <div class="flex items-end gap-3">
            <div>
                <p class="text-4xl font-black font-heading" style="color:#F5A623;">{{ $accuracy }}<span class="text-xl">٪</span></p>
                <p class="text-[10px] mt-0.5" style="color:rgba(185,203,185,0.5);">پیش‌بینی درست از کل</p>
            </div>
            <div class="pb-1">
                <p class="text-2xl font-black font-heading" style="color:rgba(245,166,35,0.6);">{{ $exactRate }}<span class="text-sm">٪</span></p>
                <p class="text-[10px]" style="color:rgba(185,203,185,0.4);">دقیق (نتیجه کامل)</p>
            </div>
        </div>
        <div class="w-full h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
            <div class="h-full rounded-full" style="width:{{ $accuracy }}%;background:linear-gradient(90deg,#D97706,#F5A623);transition:width 1s ease;"></div>
        </div>
    </div>

</section>

{{-- ── Main Content + Sidebar ──────────────────────────────── --}}
<section class="grid grid-cols-1 lg:grid-cols-12 gap-5">

    <div class="lg:col-span-8 space-y-5">

        {{-- بازی‌های پیش رو --}}
        @if($upcomingGames->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold font-heading flex items-center gap-2 text-white">
                    <span class="material-symbols-outlined" style="color:#00e476;">schedule</span>
                    بازی‌های پیش‌رو بدون پیش‌بینی
                </h2>
                <a href="{{ route('games.index') }}" class="text-sm font-bold" style="color:#00e476;">مشاهده همه</a>
            </div>
            <div class="space-y-4">
                @foreach($upcomingGames->take(3) as $game)
                <a href="{{ route('games.show', $game) }}" class="block liquid-glass card-glow rounded-2xl p-5 transition-all" style="border-right:4px solid #00e476;cursor:pointer;" onmouseover="this.style.borderColor='#00ff85'" onmouseout="this.style.borderColor='#00e476'">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="flex-1 flex items-center justify-center gap-6 w-full">
                            <div class="text-center">
                                <div class="w-14 h-14 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden"
                                     style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                                    @if($game->homeTeam->flag_url)
                                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="text-sm font-black font-heading text-white hidden w-full h-full items-center justify-center">{{ $game->homeTeam->code }}</span>
                                    @else
                                        <span class="text-sm font-black font-heading text-white">{{ $game->homeTeam->code }}</span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-white">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                            </div>
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs px-2 py-0.5 rounded-full font-bold font-mono"
                                          style="background:rgba(0,228,118,0.1);color:#00e476;">
                                        {{ $game->scheduled_at?->format('j M — H:i') }}
                                    </span>
                                </div>
                                <span class="text-white/30 text-lg font-bold">vs</span>
                            </div>
                            <div class="text-center">
                                <div class="w-14 h-14 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden"
                                     style="background:rgba(255,255,255,0.06);border:2px solid rgba(255,255,255,0.1);">
                                    @if($game->awayTeam->flag_url)
                                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                        <span class="text-sm font-black font-heading text-white hidden w-full h-full items-center justify-center">{{ $game->awayTeam->code }}</span>
                                    @else
                                        <span class="text-sm font-black font-heading text-white">{{ $game->awayTeam->code }}</span>
                                    @endif
                                </div>
                                <p class="text-sm font-bold text-white">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="block text-center px-6 py-2.5 rounded-xl font-bold text-sm"
                               style="background:#00e476;color:#003919;">
                                ثبت پیش‌بینی
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- پیش‌بینی‌های اخیر --}}
        @if($myPreds->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold font-heading flex items-center gap-2 text-white mb-4">
                <span class="material-symbols-outlined" style="color:#00e476;">history</span>
                پیش‌بینی‌های اخیر
            </h2>
            <div class="liquid-glass rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                            <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.7);">بازی</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">پیش‌بینی</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">نتیجه</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.7);">امتیاز</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($myPreds->take(8) as $pred)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);"
                            onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                            onmouseout="this.style.background=''">
                            <td class="px-4 py-3 font-semibold text-white text-xs">
                                {{ $pred->game->homeTeam->code }} <span style="color:rgba(255,255,255,0.3);">vs</span> {{ $pred->game->awayTeam->code }}
                            </td>
                            <td class="px-4 py-3 text-center font-heading font-black text-white">
                                {{ $pred->home_score }}–{{ $pred->away_score }}
                            </td>
                            <td class="px-4 py-3 text-center font-heading font-black" style="color:rgba(221,226,240,0.6);">
                                @if($pred->game->status === 'finished')
                                    {{ $pred->game->home_score }}–{{ $pred->game->away_score }}
                                @else
                                    <span style="color:rgba(255,255,255,0.2);">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($pred->points_earned !== null)
                                    @php
                                        $pc = $pred->points_earned >= 7
                                            ? 'rgba(0,228,118,0.15);color:#00e476;border:1px solid rgba(0,228,118,0.3)'
                                            : ($pred->points_earned >= 5
                                                ? 'rgba(77,159,255,0.15);color:#4D9FFF;border:1px solid rgba(77,159,255,0.3)'
                                                : ($pred->points_earned >= 2
                                                    ? 'rgba(255,255,255,0.08);color:#b9cbb9;border:1px solid rgba(255,255,255,0.12)'
                                                    : 'rgba(255,90,90,0.15);color:#FF8A8A;border:1px solid rgba(255,90,90,0.3)'));
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold" style="background:{{ $pc }}">
                                        +{{ $pred->points_earned }}
                                    </span>
                                @else
                                    <span style="color:rgba(255,255,255,0.2);font-size:12px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($myPreds->isEmpty() && $upcomingGames->isEmpty())
        <div class="liquid-glass rounded-2xl p-16 text-center">
            <span class="material-symbols-outlined text-5xl mb-4 block" style="color:rgba(0,228,118,0.4);">sports_soccer</span>
            <p class="text-sm mb-5" style="color:rgba(185,203,185,0.7);">هنوز پیش‌بینی‌ای ثبت نشده</p>
            <a href="{{ route('games.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all"
               style="background:#00e476;color:#003919;"
               onmouseover="this.style.boxShadow='0 0 25px rgba(0,228,118,0.4)'"
               onmouseout="this.style.boxShadow=''">
                <span class="material-symbols-outlined text-base">add_circle</span>
                شروع پیش‌بینی
            </a>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-4 space-y-5">

        {{-- کارت امتیازات --}}
        <div class="liquid-glass rounded-3xl p-5 space-y-4" style="border-color:rgba(0,228,118,0.15);">
            <h3 class="font-bold font-heading text-white flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">query_stats</span>
                خلاصه عملکرد
            </h3>

            {{-- امتیاز و رتبه کنار هم --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="p-4 rounded-2xl text-center" style="background:linear-gradient(135deg,rgba(0,228,118,0.08),rgba(0,228,118,0.03));border:1px solid rgba(0,228,118,0.15);">
                    <p class="text-3xl font-black font-heading" style="color:#00e476;">{{ $user->total_score ?? 0 }}</p>
                    <p class="text-[10px] mt-1" style="color:rgba(0,228,118,0.6);">امتیاز کل</p>
                </div>
                <div class="p-4 rounded-2xl text-center" style="background:linear-gradient(135deg,rgba(77,159,255,0.08),rgba(77,159,255,0.03));border:1px solid rgba(77,159,255,0.15);">
                    <p class="text-3xl font-black font-heading" style="color:#4D9FFF;">#{{ $rank }}</p>
                    <p class="text-[10px] mt-1" style="color:rgba(77,159,255,0.6);">رتبه در لیگ</p>
                </div>
            </div>

            {{-- نوارهای پیش‌بینی --}}
            <div class="space-y-2.5">
                @php
                    $rows = [
                        ['label'=>'کل پیش‌بینی‌ها','val'=>$totalPreds,'max'=>max($totalPreds,1),'color'=>'rgba(185,203,185,0.5)','bg'=>'rgba(255,255,255,0.06)'],
                        ['label'=>'پیش‌بینی درست','val'=>$correctPreds,'max'=>max($totalPreds,1),'color'=>'#00e476','bg'=>'rgba(0,228,118,0.08)'],
                        ['label'=>'پیش‌بینی دقیق','val'=>$exactPreds,'max'=>max($totalPreds,1),'color'=>'#F5A623','bg'=>'rgba(245,166,35,0.08)'],
                    ];
                @endphp
                @foreach($rows as $row)
                <div>
                    <div class="flex justify-between text-[11px] mb-1">
                        <span style="color:rgba(185,203,185,0.6);">{{ $row['label'] }}</span>
                        <span class="font-mono font-bold" style="color:{{ $row['color'] }};">{{ $row['val'] }}</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                        <div class="h-full rounded-full transition-all duration-1000" style="width:{{ $row['max'] > 0 ? round($row['val']/$row['max']*100) : 0 }}%;background:{{ $row['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('leaderboard') }}"
               class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl font-bold text-sm transition-all"
               style="background:rgba(0,228,118,0.08);color:#00e476;border:1px solid rgba(0,228,118,0.2);"
               onmouseover="this.style.background='rgba(0,228,118,0.15)'"
               onmouseout="this.style.background='rgba(0,228,118,0.08)'">
                <span class="material-symbols-outlined text-base">leaderboard</span>
                جدول رده‌بندی
            </a>
        </div>

        {{-- لینک سریع به پیش‌بینی قهرمان --}}
        <a href="{{ route('tournament.prediction') }}"
           class="relative rounded-3xl overflow-hidden block cursor-pointer group"
           style="min-height:140px;">
            @if(file_exists(public_path('images/stadium.jpg')))
                <img src="{{ asset('images/stadium.jpg') }}"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                     alt="stadium">
            @else
                <div class="absolute inset-0"
                     style="background:linear-gradient(135deg,#001a0d 0%,#003919 40%,#001428 100%);">
                    <div class="absolute inset-0" style="background:radial-gradient(ellipse at 30% 50%,rgba(0,228,118,0.25) 0%,transparent 65%);"></div>
                    <div class="absolute top-3 left-6 w-1.5 h-1.5 rounded-full opacity-70" style="background:#00e476;box-shadow:0 0 8px #00e476;"></div>
                    <div class="absolute top-5 left-16 w-1 h-1 rounded-full opacity-40" style="background:#00e476;"></div>
                    <div class="absolute top-2 right-8 w-1 h-1 rounded-full opacity-50" style="background:#ffe16d;box-shadow:0 0 6px #ffe16d;"></div>
                    <div class="absolute top-8 right-4 w-1.5 h-1.5 rounded-full opacity-30" style="background:#ffe16d;"></div>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
            <div class="relative p-5 flex flex-col justify-end h-full" style="min-height:140px;">
                <p class="text-xs font-bold mb-1" style="color:#00e476;">مسابقه ویژه</p>
                <h4 class="font-heading font-bold text-white text-base leading-tight">قهرمان را پیش‌بینی کنید!</h4>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6);">جایزه ویژه امتیازی در انتظار شماست</p>
            </div>
        </a>

        {{-- ── چت گروهی ── --}}
        <div class="liquid-glass rounded-3xl overflow-hidden flex flex-col" style="border-color:rgba(77,159,255,0.15);height:240px;" x-data="dashChat()">
            <div class="flex items-center justify-between px-3 py-2 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <h3 class="font-bold font-heading text-white flex items-center gap-2 text-xs">
                    <span class="material-symbols-outlined text-sm" style="color:#4D9FFF;">forum</span>
                    چت گروهی
                </h3>
                <a href="{{ route('chat') }}" class="text-[10px] font-bold flex items-center gap-1" style="color:rgba(77,159,255,0.7);">
                    <span class="material-symbols-outlined text-xs">open_in_new</span>کامل
                </a>
            </div>

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto px-2 py-1.5 space-y-1.5" id="dash-chat-msgs">
                @foreach($chatMessages as $msg)
                @php $isMe = $msg->user_id === auth()->id(); @endphp
                <div class="flex {{ $isMe ? 'justify-start' : 'justify-end' }} gap-1.5">
                    @if(!$isMe)
                    <div class="max-w-[85%]">
                        <div class="rounded-xl px-2.5 py-1.5 text-[11px]"
                             style="background:rgba(255,255,255,0.07);color:rgba(221,226,240,0.9);">
                            <p class="font-bold text-[9px] mb-0.5" style="color:#4D9FFF;">{{ $msg->user->name }}</p>
                            {{ $msg->body }}
                        </div>
                    </div>
                    @else
                    <div class="max-w-[85%]">
                        <div class="rounded-xl px-2.5 py-1.5 text-[11px]" style="background:rgba(0,228,118,0.12);color:rgba(221,226,240,0.9);">
                            <p class="font-bold text-[9px] mb-0.5" style="color:#00e476;">شما</p>
                            {{ $msg->body }}
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
                @if($chatMessages->isEmpty())
                <p class="text-center text-[11px] py-3" style="color:rgba(185,203,185,0.3);">اولین پیام را بفرست!</p>
                @endif
            </div>

            {{-- Input --}}
            <div class="flex items-center gap-1.5 px-2 py-2 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.07);">
                <input type="text" x-model="body" @keydown.enter="send()"
                       placeholder="پیام..." maxlength="200"
                       class="flex-1 text-[11px] px-2.5 py-1.5 rounded-lg outline-none"
                       style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#dde2f0;">
                <button @click="send()" :disabled="!body.trim()"
                        class="p-1.5 rounded-lg transition-all cursor-pointer flex-shrink-0"
                        style="background:rgba(77,159,255,0.15);color:#4D9FFF;">
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </div>
        </div>

    </div>

</section>

@push('scripts')
<script>
function dashChat() {
    return {
        body: '',
        lastId: {{ $chatMessages->last()?->id ?? 0 }},
        async send() {
            const msg = this.body.trim();
            if (!msg) return;
            this.body = '';
            try {
                const res = await fetch('{{ route('chat.store') }}', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
                    body: JSON.stringify({body: msg})
                });
                if (res.ok) {
                    const data = await res.json();
                    this.appendMsg({body:msg, name:'شما', isMe:true});
                    this.lastId = data.message?.id ?? this.lastId;
                }
            } catch(e) {}
        },
        appendMsg({body, name, isMe}) {
            const wrap = document.getElementById('dash-chat-msgs');
            const div = document.createElement('div');
            div.className = 'flex ' + (isMe ? 'justify-start' : 'justify-end') + ' gap-2';
            div.innerHTML = `<div class="max-w-[80%]"><div class="rounded-2xl px-3 py-2 text-xs" style="background:${isMe?'rgba(0,228,118,0.12)':'rgba(255,255,255,0.07)'};color:rgba(221,226,240,0.9);"><p class="font-bold text-[10px] mb-1" style="color:${isMe?'#00e476':'#4D9FFF'};">${name}</p>${body}</div></div>`;
            wrap.appendChild(div);
            wrap.scrollTop = wrap.scrollHeight;
        },
        init() {
            const wrap = document.getElementById('dash-chat-msgs');
            if (wrap) wrap.scrollTop = wrap.scrollHeight;
            setInterval(async () => {
                try {
                    const res = await fetch(`{{ route('chat.messages') }}?since=${this.lastId}`);
                    const data = await res.json();
                    data.messages?.forEach(m => {
                        if (!m.is_me) {
                            this.appendMsg({body: m.body, name: m.user_name, isMe: false});
                            this.lastId = m.id;
                        } else {
                            this.lastId = Math.max(this.lastId, m.id);
                        }
                    });
                } catch(e) {}
            }, 5000);
        }
    };
}
</script>
@endpush

@endsection
