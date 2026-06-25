@extends('layouts.app')

@section('title', 'جدول رده‌بندی')

@section('content')

<div class="mb-6 animate-slide-up">
    <div class="flex items-center gap-3 mb-1">
        <div class="w-1 h-6 rounded-full" style="background:linear-gradient(180deg,#F5A623,#A78BFA);"></div>
        <h1 class="text-xl font-black font-heading text-brand-text">جدول رده‌بندی</h1>
    </div>
    <p class="text-xs text-brand-muted mr-4">برای مقایسه رو‌در‌رو روی هر نفر کلیک کنید</p>
</div>

{{-- Top 3 Podium --}}
@if($users->count() >= 3)
<div class="grid grid-cols-3 gap-3 mb-6" style="animation:slide-up .5s .05s cubic-bezier(.16,1,.3,1) both;">

    {{-- 2nd --}}
    <div class="glass-card rounded-2xl p-4 text-center cursor-pointer mt-8"
         onclick="openH2H({{ $users[1]->id }}, '{{ addslashes($users[1]->name) }}')"
         style="border-color:rgba(148,163,184,0.25);"
         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(148,163,184,0.12)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="w-12 h-12 rounded-2xl mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">
            {{ mb_strtoupper(mb_substr($users[1]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(148,163,184,0.15);border:1px solid rgba(148,163,184,0.3);color:#CBD5E1;">2</div>
        <p class="text-xs font-bold text-brand-text truncate">{{ $users[1]->name }}</p>
        <p class="text-[10px] text-brand-muted mt-0.5 truncate">{{ $users[1]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#94A3B8;">{{ $users[1]->total_score }}</p>
    </div>

    {{-- 1st --}}
    <div class="rounded-2xl p-4 text-center cursor-pointer relative overflow-hidden transition-all duration-300"
         onclick="openH2H({{ $users[0]->id }}, '{{ addslashes($users[0]->name) }}')"
         style="background:linear-gradient(180deg,rgba(245,166,35,0.1),rgba(10,16,32,0.9));border:1px solid rgba(245,166,35,0.35);"
         onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 24px 48px rgba(245,166,35,0.2)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="absolute top-0 inset-x-0 h-20 pointer-events-none"
             style="background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(245,166,35,0.18),transparent);"></div>
        <svg class="w-6 h-6 mx-auto mb-1 relative" viewBox="0 0 24 24" fill="#F5A623">
            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm2 3h10v2H7v-2z"/>
        </svg>
        <div class="w-14 h-14 rounded-2xl mx-auto mb-2 flex items-center justify-center text-xl font-black font-heading relative"
             style="background:linear-gradient(135deg,#92400E,#D97706,#F59E0B);color:#0a0a0a;box-shadow:0 0 28px rgba(245,166,35,0.4);">
            {{ mb_strtoupper(mb_substr($users[0]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-black"
             style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 14px rgba(245,166,35,0.4);">1</div>
        <p class="text-sm font-black text-brand-text truncate">{{ $users[0]->name }}</p>
        <p class="text-[10px] text-brand-muted mt-0.5 truncate">{{ $users[0]->department ?: '—' }}</p>
        <p class="text-3xl font-black font-heading mt-2 gradient-text-gold">{{ $users[0]->total_score }}</p>
    </div>

    {{-- 3rd --}}
    <div class="glass-card rounded-2xl p-4 text-center cursor-pointer mt-12"
         onclick="openH2H({{ $users[2]->id }}, '{{ addslashes($users[2]->name) }}')"
         style="border-color:rgba(194,65,12,0.25);"
         onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(194,65,12,0.1)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="w-12 h-12 rounded-2xl mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">
            {{ mb_strtoupper(mb_substr($users[2]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(194,65,12,0.15);border:1px solid rgba(194,65,12,0.3);color:#FDBA74;">3</div>
        <p class="text-xs font-bold text-brand-text truncate">{{ $users[2]->name }}</p>
        <p class="text-[10px] text-brand-muted mt-0.5 truncate">{{ $users[2]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#FB923C;">{{ $users[2]->total_score }}</p>
    </div>
</div>
@endif

{{-- Full Table --}}
<div class="glass rounded-2xl overflow-hidden" style="animation:slide-up .5s .1s cubic-bezier(.16,1,.3,1) both;">
    <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
        <div class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#F5A623,#00E5A0);"></div>
        <h3 class="font-black text-sm font-heading text-brand-text">همه رتبه‌بندی‌ها</h3>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                <th class="px-4 py-3 text-right text-xs font-bold text-brand-subtle w-12">#</th>
                <th class="px-4 py-3 text-right text-xs font-bold text-brand-subtle">نام</th>
                <th class="px-4 py-3 text-right text-xs font-bold text-brand-subtle hidden sm:table-cell">دپارتمان</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-brand-subtle">امتیاز</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-brand-subtle hidden md:table-cell">پیش‌بینی‌ها</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $u)
            @php $isMe = $u->id === auth()->id(); @endphp
            <tr class="cursor-pointer transition-all duration-150"
                style="{{ $isMe ? 'background:rgba(0,229,160,0.04);border-right:3px solid #00E5A0;' : '' }}"
                onclick="openH2H({{ $u->id }}, '{{ addslashes($u->name) }}')"
                onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                onmouseout="this.style.background='{{ $isMe ? 'rgba(0,229,160,0.04)' : '' }}'">
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
                        <span class="text-brand-subtle text-xs font-semibold">{{ $i+1 }}</span>
                    @endif
                </td>
                <td class="px-4 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                             style="{{ $isMe ? 'background:linear-gradient(135deg,#00BF85,#00E5A0);color:#0a0a0a;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#F0F4FF;' }}">
                            {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                        </div>
                        <span class="font-semibold {{ $isMe ? '' : 'text-brand-text' }}"
                              style="{{ $isMe ? 'color:#00E5A0;' : '' }}">{{ $u->name }}</span>
                        @if($isMe)<span class="badge badge-green text-[10px] mr-1">شما</span>@endif
                    </div>
                </td>
                <td class="px-4 py-3.5 text-brand-muted text-xs hidden sm:table-cell"
                    style="border-bottom:1px solid rgba(255,255,255,0.04);">{{ $u->department ?: '—' }}</td>
                <td class="px-4 py-3.5 text-center" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    @if($i===0)
                        <span class="text-xl font-black font-heading gradient-text-gold">{{ $u->total_score }}</span>
                    @elseif($i<3)
                        <span class="text-lg font-black font-heading" style="color:#94A3B8;">{{ $u->total_score }}</span>
                    @else
                        <span class="font-bold text-brand-text">{{ $u->total_score }}</span>
                    @endif
                </td>
                <td class="px-4 py-3.5 text-center hidden md:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <span class="badge badge-gray">{{ $predictions->get($u->id, collect())->count() }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-16 text-center text-sm text-brand-subtle">هنوز کاربری ثبت‌نام نکرده است.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Head-to-Head Modal --}}
<div id="h2h-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);">
    <div class="glass-strong rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col" style="border-color:rgba(245,166,35,0.2);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div>
                <h2 class="font-black text-base font-heading text-brand-text" id="h2h-title">مقایسه رو‌در‌رو</h2>
                <p class="text-xs text-brand-muted mt-0.5" id="h2h-subtitle"></p>
            </div>
            <button onclick="closeH2H()" class="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer transition-all"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);"
                    onmouseover="this.style.background='rgba(255,90,90,0.15)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                <svg class="w-4 h-4 text-brand-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Score summary bar --}}
        <div class="px-6 py-4 grid grid-cols-3 gap-4 text-center" style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div>
                <p class="text-xs text-brand-muted mb-1">{{ auth()->user()->name }}</p>
                <p class="text-3xl font-black font-heading" id="my-score-display" style="color:#00E5A0;">—</p>
            </div>
            <div class="flex flex-col items-center justify-center">
                <p class="text-xs text-brand-subtle mb-1">امتیاز کل</p>
                <div class="badge badge-gray text-[10px]" id="games-count">۰ بازی</div>
            </div>
            <div>
                <p class="text-xs text-brand-muted mb-1" id="opp-name-display">—</p>
                <p class="text-3xl font-black font-heading" id="opp-score-display" style="color:#F5A623;">—</p>
            </div>
        </div>

        <div class="overflow-y-auto flex-1 p-4" id="h2h-body">
            <p class="text-center text-brand-muted text-sm py-8">در حال بارگذاری...</p>
        </div>
    </div>
</div>

@php
// Pass PHP data as JSON for JS
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

<script>
const ME_ID = {{ auth()->id() }};
const ME_NAME = @json(auth()->user()->name);
const ALL_PREDS = @json($jsPreds);
const ALL_GAMES = @json($jsGames);

function openH2H(oppId, oppName) {
    if(oppId === ME_ID) {
        // Show own predictions only
        document.getElementById('h2h-title').textContent = 'پیش‌بینی‌های من';
        document.getElementById('h2h-subtitle').textContent = '';
    } else {
        document.getElementById('h2h-title').textContent = 'مقایسه رو‌در‌رو';
        document.getElementById('h2h-subtitle').textContent = ME_NAME + ' در برابر ' + oppName;
    }
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

function renderH2H(oppId, oppName) {
    const myPreds = ALL_PREDS[ME_ID] || {};
    const oppPreds = ALL_PREDS[oppId] || {};

    let myTotal = 0, oppTotal = 0, rows = '';

    ALL_GAMES.forEach(g => {
        const myP = myPreds[g.id];
        const oppP = oppPreds[g.id];
        if(!myP && !oppP) return;

        myTotal += myP?.pts ?? 0;
        oppTotal += oppP?.pts ?? 0;

        const myPredStr = myP ? `${myP.h}–${myP.a}` : '—';
        const oppPredStr = oppP ? `${oppP.h}–${oppP.a}` : '—';
        const realStr = `${g.rh}–${g.ra}`;

        const myColor = myP?.pts >= 10 ? '#00E5A0' : myP?.pts >= 7 ? '#4D9FFF' : myP?.pts >= 5 ? '#F5A623' : myP?.pts >= 2 ? '#8BA0C4' : '#FF5A5A';
        const oppColor = oppP?.pts >= 10 ? '#00E5A0' : oppP?.pts >= 7 ? '#4D9FFF' : oppP?.pts >= 5 ? '#F5A623' : oppP?.pts >= 2 ? '#8BA0C4' : '#FF5A5A';

        rows += `
        <div class="glass-card rounded-xl p-3 mb-2">
            <div class="flex items-center justify-between gap-2">
                <div class="flex-1 text-center">
                    <span class="text-lg font-black font-heading" style="color:${myColor};">${myPredStr}</span>
                    ${myP ? `<div class="text-[10px] mt-0.5" style="color:${myColor};">+${myP.pts ?? 0}</div>` : ''}
                </div>
                <div class="text-center flex-shrink-0 px-2">
                    <p class="text-[10px] text-brand-muted">${g.date}</p>
                    <p class="text-sm font-bold text-brand-text font-heading">${g.home} <span class="text-brand-subtle">vs</span> ${g.away}</p>
                    <p class="text-xs text-brand-muted">نتیجه: <span class="font-bold text-brand-text">${realStr}</span></p>
                </div>
                <div class="flex-1 text-center">
                    <span class="text-lg font-black font-heading" style="color:${oppColor};">${oppPredStr}</span>
                    ${oppP ? `<div class="text-[10px] mt-0.5" style="color:${oppColor};">+${oppP.pts ?? 0}</div>` : ''}
                </div>
            </div>
        </div>`;
    });

    const count = ALL_GAMES.filter(g => myPreds[g.id] || oppPreds[g.id]).length;
    document.getElementById('my-score-display').textContent = myTotal;
    document.getElementById('opp-score-display').textContent = oppTotal;
    document.getElementById('games-count').textContent = count + ' بازی';

    document.getElementById('h2h-body').innerHTML = rows || '<p class="text-center text-brand-muted text-sm py-8">هنوز پیش‌بینی مشترکی وجود ندارد</p>';
}

// Close on backdrop click
document.getElementById('h2h-modal').addEventListener('click', function(e) {
    if(e.target === this) closeH2H();
});
</script>

@endsection
