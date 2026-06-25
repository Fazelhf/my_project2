@extends('layouts.app')

@section('title', 'جدول رده‌بندی')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3 mb-1">
        <span class="material-symbols-outlined text-3xl" style="color:#00e476;">leaderboard</span>
        جدول رده‌بندی
    </h1>
    <p class="text-xs mr-10" style="color:rgba(185,203,185,0.6);">برای مقایسه رو‌در‌رو روی هر نفر کلیک کنید</p>
</div>

{{-- ── Top 3 Podium ─────────────────────────────────────────── --}}
@if($users->count() >= 3)
<div class="grid grid-cols-3 gap-3 mb-6">

    {{-- 2nd --}}
    <div class="liquid-glass rounded-2xl p-4 text-center cursor-pointer mt-8 transition-all duration-300"
         onclick="openH2H({{ $users[1]->id }}, '{{ addslashes($users[1]->name) }}')"
         style="border-color:rgba(148,163,184,0.25);"
         onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='rgba(148,163,184,0.5)'"
         onmouseout="this.style.transform='';this.style.borderColor='rgba(148,163,184,0.25)'">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">
            {{ mb_strtoupper(mb_substr($users[1]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(148,163,184,0.15);border:1px solid rgba(148,163,184,0.3);color:#CBD5E1;">2</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[1]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.6);">{{ $users[1]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#94A3B8;">{{ $users[1]->total_score }}</p>
    </div>

    {{-- 1st --}}
    <div class="rounded-2xl p-4 text-center cursor-pointer relative overflow-hidden transition-all duration-300"
         onclick="openH2H({{ $users[0]->id }}, '{{ addslashes($users[0]->name) }}')"
         style="background:linear-gradient(180deg,rgba(245,166,35,0.1),rgba(14,20,29,0.9));border:1px solid rgba(245,166,35,0.35);"
         onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 24px 48px rgba(245,166,35,0.2)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="absolute top-0 inset-x-0 h-20 pointer-events-none"
             style="background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(245,166,35,0.18),transparent);"></div>
        <span class="material-symbols-outlined text-2xl mb-1 relative block" style="color:#F5A623;">emoji_events</span>
        <div class="w-14 h-14 rounded-full mx-auto mb-2 flex items-center justify-center text-xl font-black font-heading relative"
             style="background:linear-gradient(135deg,#92400E,#D97706,#F59E0B);color:#0a0a0a;box-shadow:0 0 28px rgba(245,166,35,0.4);">
            {{ mb_strtoupper(mb_substr($users[0]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-black"
             style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 14px rgba(245,166,35,0.4);">1</div>
        <p class="text-sm font-black text-white truncate">{{ $users[0]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.6);">{{ $users[0]->department ?: '—' }}</p>
        <p class="text-3xl font-black font-heading mt-2" style="background:linear-gradient(135deg,#D97706,#F5A623,#FCD34D);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $users[0]->total_score }}</p>
    </div>

    {{-- 3rd --}}
    <div class="liquid-glass rounded-2xl p-4 text-center cursor-pointer mt-12 transition-all duration-300"
         onclick="openH2H({{ $users[2]->id }}, '{{ addslashes($users[2]->name) }}')"
         style="border-color:rgba(194,65,12,0.25);"
         onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='rgba(194,65,12,0.4)'"
         onmouseout="this.style.transform='';this.style.borderColor='rgba(194,65,12,0.25)'">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">
            {{ mb_strtoupper(mb_substr($users[2]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(194,65,12,0.15);border:1px solid rgba(194,65,12,0.3);color:#FDBA74;">3</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[2]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.6);">{{ $users[2]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#FB923C;">{{ $users[2]->total_score }}</p>
    </div>
</div>
@endif

{{-- ── Full Table ───────────────────────────────────────────── --}}
<div class="liquid-glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="w-2 h-5 rounded-full" style="background:linear-gradient(180deg,#00e476,#4D9FFF);"></div>
        <h3 class="font-black text-sm font-heading text-white">همه رتبه‌بندی‌ها</h3>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                <th class="px-4 py-3 text-right text-xs font-bold w-12" style="color:rgba(185,203,185,0.5);">#</th>
                <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.5);">نام</th>
                <th class="px-4 py-3 text-right text-xs font-bold hidden sm:table-cell" style="color:rgba(185,203,185,0.5);">دپارتمان</th>
                <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">امتیاز</th>
                <th class="px-4 py-3 text-center text-xs font-bold hidden md:table-cell" style="color:rgba(185,203,185,0.5);">پیش‌بینی‌ها</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $u)
            @php $isMe = $u->id === auth()->id(); @endphp
            <tr class="cursor-pointer transition-all duration-150"
                style="{{ $isMe ? 'background:rgba(0,228,118,0.04);border-right:3px solid #00e476;' : '' }}"
                onclick="openH2H({{ $u->id }}, '{{ addslashes($u->name) }}')"
                onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                onmouseout="this.style.background='{{ $isMe ? 'rgba(0,228,118,0.04)' : '' }}'">
                <td class="px-4 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    @if($i===0)
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                             style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 12px rgba(245,166,35,0.3);">1</div>
                    @elseif($i===1)
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                             style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">2</div>
                    @elseif($i===2)
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading"
                             style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">3</div>
                    @else
                        <span class="text-xs font-semibold" style="color:rgba(185,203,185,0.4);">{{ $i+1 }}</span>
                    @endif
                </td>
                <td class="px-4 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                             style="{{ $isMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#dde2f0;' }}">
                            {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                        </div>
                        <span class="font-semibold" style="{{ $isMe ? 'color:#00e476;' : 'color:#dde2f0;' }}">{{ $u->name }}</span>
                        @if($isMe)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                              style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.25);">شما</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3.5 text-xs hidden sm:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);color:rgba(185,203,185,0.6);">{{ $u->department ?: '—' }}</td>
                <td class="px-4 py-3.5 text-center" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    @if($i===0)
                        <span class="text-xl font-black font-heading" style="background:linear-gradient(135deg,#D97706,#F5A623,#FCD34D);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">{{ $u->total_score }}</span>
                    @elseif($i<3)
                        <span class="text-lg font-black font-heading" style="color:#94A3B8;">{{ $u->total_score }}</span>
                    @else
                        <span class="font-bold text-white">{{ $u->total_score }}</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 text-center hidden md:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold font-mono"
                          style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);border:1px solid rgba(255,255,255,0.1);">
                        {{ $predictions->get($u->id, collect())->count() }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-16 text-center text-sm" style="color:rgba(185,203,185,0.4);">هنوز کاربری ثبت‌نام نکرده است.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── H2H Modal ────────────────────────────────────────────── --}}
<div id="h2h-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.75);backdrop-filter:blur(10px);">
    <div class="liquid-glass rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col" style="border-color:rgba(0,228,118,0.2);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div>
                <h2 class="font-black text-base font-heading text-white" id="h2h-title">مقایسه رو‌در‌رو</h2>
                <p class="text-xs mt-0.5" id="h2h-subtitle" style="color:rgba(185,203,185,0.6);"></p>
            </div>
            <button onclick="closeH2H()" class="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer transition-all"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);"
                    onmouseover="this.style.background='rgba(255,90,90,0.15)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                <span class="material-symbols-outlined text-base" style="color:rgba(185,203,185,0.7);">close</span>
            </button>
        </div>

        <div class="px-6 py-4 grid grid-cols-3 gap-4 text-center" style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div>
                <p class="text-xs mb-1" style="color:rgba(185,203,185,0.6);">{{ auth()->user()->name }}</p>
                <p class="text-3xl font-black font-heading" id="my-score-display" style="color:#00e476;">—</p>
            </div>
            <div class="flex flex-col items-center justify-center">
                <p class="text-xs mb-1" style="color:rgba(185,203,185,0.4);">امتیاز کل</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono"
                      id="games-count"
                      style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);border:1px solid rgba(255,255,255,0.1);">۰ بازی</span>
            </div>
            <div>
                <p class="text-xs mb-1" id="opp-name-display" style="color:rgba(185,203,185,0.6);">—</p>
                <p class="text-3xl font-black font-heading" id="opp-score-display" style="color:#F5A623;">—</p>
            </div>
        </div>

        <div class="overflow-y-auto flex-1 p-4" id="h2h-body">
            <p class="text-center text-sm py-8" style="color:rgba(185,203,185,0.5);">در حال بارگذاری...</p>
        </div>
    </div>
</div>

@php
$jsUsers = $users->map(fn($u) => ['id'=>$u->id,'name'=>$u->name,'score'=>$u->total_score]);
$jsPreds = [];
foreach($predictions as $userId => $gamePreds) {
    foreach($gamePreds as $gameId => $pred) {
        $jsPreds[$userId][$gameId] = ['h'=>$pred->home_score,'a'=>$pred->away_score,'pts'=>$pred->points_earned];
    }
}
$jsGames = $finishedGames->map(fn($g) => [
    'id'=>$g->id,
    'home'=>$g->homeTeam?->code ?? '?',
    'away'=>$g->awayTeam?->code ?? '?',
    'rh'=>$g->home_score,
    'ra'=>$g->away_score,
    'date'=>$g->scheduled_at?->format('j M'),
]);
@endphp

@push('scripts')
<script>
const ME_ID = {{ auth()->id() }};
const ME_NAME = @json(auth()->user()->name);
const ALL_PREDS = @json($jsPreds);
const ALL_GAMES = @json($jsGames);

function openH2H(oppId, oppName) {
    document.getElementById('h2h-title').textContent = oppId === ME_ID ? 'پیش‌بینی‌های من' : 'مقایسه رو‌در‌رو';
    document.getElementById('h2h-subtitle').textContent = oppId === ME_ID ? '' : ME_NAME + ' در برابر ' + oppName;
    document.getElementById('opp-name-display').textContent = oppName;
    const modal = document.getElementById('h2h-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    renderH2H(oppId, oppName);
}

function closeH2H() {
    const modal = document.getElementById('h2h-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function ptColor(pts) {
    if (pts >= 10) return '#00e476';
    if (pts >= 7) return '#4D9FFF';
    if (pts >= 5) return '#F5A623';
    if (pts >= 2) return '#b9cbb9';
    return '#FF8A8A';
}

function renderH2H(oppId, oppName) {
    const myPreds = ALL_PREDS[ME_ID] || {};
    const oppPreds = ALL_PREDS[oppId] || {};
    let myTotal = 0, oppTotal = 0, rows = '';

    ALL_GAMES.forEach(g => {
        const myP = myPreds[g.id];
        const oppP = oppPreds[g.id];
        if (!myP && !oppP) return;
        myTotal += myP?.pts ?? 0;
        oppTotal += oppP?.pts ?? 0;

        rows += `
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:12px;margin-bottom:8px;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                <div style="flex:1;text-align:center;">
                    <div style="font-size:18px;font-weight:900;color:${ptColor(myP?.pts ?? 0)};">${myP ? `${myP.h}–${myP.a}` : '—'}</div>
                    ${myP ? `<div style="font-size:10px;color:${ptColor(myP.pts ?? 0)};">+${myP.pts ?? 0}</div>` : ''}
                </div>
                <div style="text-align:center;flex-shrink:0;padding:0 8px;">
                    <div style="font-size:10px;color:rgba(185,203,185,0.5);">${g.date}</div>
                    <div style="font-size:13px;font-weight:700;color:#dde2f0;">${g.home} <span style="color:rgba(255,255,255,0.3);">vs</span> ${g.away}</div>
                    <div style="font-size:11px;color:rgba(185,203,185,0.5);">نتیجه: <span style="font-weight:700;color:#dde2f0;">${g.rh}–${g.ra}</span></div>
                </div>
                <div style="flex:1;text-align:center;">
                    <div style="font-size:18px;font-weight:900;color:${ptColor(oppP?.pts ?? 0)};">${oppP ? `${oppP.h}–${oppP.a}` : '—'}</div>
                    ${oppP ? `<div style="font-size:10px;color:${ptColor(oppP.pts ?? 0)};">+${oppP.pts ?? 0}</div>` : ''}
                </div>
            </div>
        </div>`;
    });

    const count = ALL_GAMES.filter(g => myPreds[g.id] || oppPreds[g.id]).length;
    document.getElementById('my-score-display').textContent = myTotal;
    document.getElementById('opp-score-display').textContent = oppTotal;
    document.getElementById('games-count').textContent = count + ' بازی';
    document.getElementById('h2h-body').innerHTML = rows || '<p style="text-align:center;color:rgba(185,203,185,0.5);padding:32px 0;">هنوز پیش‌بینی مشترکی وجود ندارد</p>';
}

document.getElementById('h2h-modal').addEventListener('click', function(e) {
    if (e.target === this) closeH2H();
});
</script>
@endpush

@endsection
