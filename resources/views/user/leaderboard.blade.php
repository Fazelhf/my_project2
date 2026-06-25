@extends('layouts.app')
@section('title', 'جدول رده‌بندی')

@section('content')

<div class="flex items-center justify-between mb-6 reveal animate-slide-up">
    <div>
        <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
            <span class="material-symbols-outlined text-3xl" style="color:#00e476;">leaderboard</span>
            جدول رده‌بندی قهرمانان
        </h1>
        <p class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">برترین پیش‌بینی‌های شرکت در مسیر جام‌جهانی ۲۰۲۶</p>
    </div>
</div>

{{-- ── Top 3 Podium ── --}}
@if($users->count() >= 3)
<div class="grid grid-cols-3 gap-3 mb-8 items-end reveal animate-slide-up stagger-1">

    {{-- 2nd --}}
    <div class="liquid-glass rounded-2xl p-5 text-center cursor-pointer transition-all duration-300 card-glow"
         onclick="openH2H({{ $users[1]->id }}, '{{ addslashes($users[1]->name) }}')"
         style="margin-top:32px;">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">
            {{ mb_strtoupper(mb_substr($users[1]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(148,163,184,0.15);border:1px solid rgba(148,163,184,0.3);color:#CBD5E1;">2</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[1]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.5);">{{ $users[1]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#94A3B8;">{{ $users[1]->total_score }}</p>
        @php $u1preds = $predictions->get($users[1]->id, collect()); @endphp
        <p class="text-[10px] font-mono mt-1" style="color:rgba(185,203,185,0.5);">
            {{ $u1preds->where('points_earned',10)->count() }} / {{ $u1preds->count() }} صحیح
        </p>
    </div>

    {{-- 1st --}}
    <div class="rounded-2xl p-5 text-center cursor-pointer relative overflow-hidden transition-all duration-300"
         onclick="openH2H({{ $users[0]->id }}, '{{ addslashes($users[0]->name) }}')"
         style="background:linear-gradient(180deg,rgba(245,166,35,0.08),rgba(14,20,29,0.9));border:1px solid rgba(245,166,35,0.3);"
         onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 24px 48px rgba(245,166,35,0.18)'"
         onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div class="absolute top-0 inset-x-0 h-16 pointer-events-none"
             style="background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(245,166,35,0.15),transparent);"></div>
        <span class="material-symbols-outlined text-2xl mb-2 block relative"
              style="color:#F5A623;font-variation-settings:'FILL' 1,'wght' 700,'GRAD' 0,'opsz' 24;">emoji_events</span>
        <div class="w-16 h-16 rounded-full mx-auto mb-2 flex items-center justify-center text-xl font-black font-heading relative"
             style="background:linear-gradient(135deg,#92400E,#D97706,#F59E0B);color:#0a0a0a;box-shadow:0 0 28px rgba(245,166,35,0.4);">
            {{ mb_strtoupper(mb_substr($users[0]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-8 h-8 rounded-full mx-auto mb-2 flex items-center justify-center text-sm font-black"
             style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 14px rgba(245,166,35,0.3);">1</div>
        <p class="text-sm font-black text-white truncate">{{ $users[0]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.6);">{{ $users[0]->department ?: '—' }}</p>
        <p class="text-3xl font-black font-heading mt-2" style="background:linear-gradient(90deg,#F5A623,#FFD700);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ $users[0]->total_score }}</p>
        @php $u0preds = $predictions->get($users[0]->id, collect()); @endphp
        <p class="text-[10px] font-mono mt-1" style="color:rgba(245,166,35,0.7);">
            {{ $u0preds->where('points_earned',10)->count() }} / {{ $u0preds->count() }} صحیح
        </p>
    </div>

    {{-- 3rd --}}
    <div class="liquid-glass rounded-2xl p-5 text-center cursor-pointer transition-all duration-300 card-glow"
         onclick="openH2H({{ $users[2]->id }}, '{{ addslashes($users[2]->name) }}')"
         style="margin-top:48px;">
        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center text-lg font-black font-heading"
             style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">
            {{ mb_strtoupper(mb_substr($users[2]->name,0,1,'UTF-8')) }}
        </div>
        <div class="w-7 h-7 rounded-full mx-auto mb-2 flex items-center justify-center text-xs font-black"
             style="background:rgba(194,65,12,0.15);border:1px solid rgba(194,65,12,0.3);color:#FDBA74;">3</div>
        <p class="text-xs font-bold text-white truncate">{{ $users[2]->name }}</p>
        <p class="text-[10px] mt-0.5 truncate" style="color:rgba(185,203,185,0.5);">{{ $users[2]->department ?: '—' }}</p>
        <p class="text-2xl font-black font-heading mt-2" style="color:#FB923C;">{{ $users[2]->total_score }}</p>
        @php $u2preds = $predictions->get($users[2]->id, collect()); @endphp
        <p class="text-[10px] font-mono mt-1" style="color:rgba(251,146,60,0.7);">
            {{ $u2preds->where('points_earned',10)->count() }} / {{ $u2preds->count() }} صحیح
        </p>
    </div>
</div>
@endif

{{-- ── Full Rankings Table ── --}}
<div class="liquid-glass rounded-2xl overflow-hidden reveal animate-slide-up stagger-2">

    <div class="px-5 py-4 flex items-center justify-between gap-4"
         style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <div class="w-2 h-5 rounded-full" style="background:linear-gradient(180deg,#00e476,#4D9FFF);"></div>
            <h3 class="font-black text-sm font-heading text-white">ردبندی کامل</h3>
            <span class="text-xs font-mono px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">{{ $users->count() }} نفر</span>
        </div>
        <div class="relative">
            <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 pointer-events-none"
                  style="right:10px;font-size:16px;color:rgba(185,203,185,0.4);">search</span>
            <input type="text" id="search-input" placeholder="جستجوی همکاران..."
                   class="stitch-input text-sm w-44"
                   style="height:36px;padding-right:34px;padding-left:10px;">
        </div>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(255,255,255,0.02);">
                <th class="px-4 py-3 text-right text-xs font-bold font-mono w-12" style="color:rgba(185,203,185,0.5);">رتبه</th>
                <th class="px-4 py-3 text-right text-xs font-bold font-mono" style="color:rgba(185,203,185,0.5);">کاربر</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono hidden md:table-cell" style="color:rgba(185,203,185,0.5);">پیش‌بینی</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono hidden sm:table-cell" style="color:rgba(185,203,185,0.5);">دقت</th>
                <th class="px-4 py-3 text-center text-xs font-bold font-mono" style="color:rgba(185,203,185,0.5);">امتیاز</th>
            </tr>
        </thead>
        <tbody id="leaderboard-body">
        @forelse($users as $i => $u)
        @php
            $isMe    = $u->id === auth()->id();
            $upreds  = $predictions->get($u->id, collect());
            $total   = $upreds->count();
            $correct = $upreds->where('points_earned', 10)->count();
            $accuracy = $total > 0 ? round($correct / $total * 100) : 0;
        @endphp
        <tr class="user-row cursor-pointer transition-all duration-150"
            data-name="{{ strtolower($u->name) }} {{ strtolower($u->department ?? '') }}"
            onclick="openH2H({{ $u->id }}, '{{ addslashes($u->name) }}')"
            onmouseover="this.style.background='rgba(255,255,255,0.03)'"
            onmouseout="this.style.background='{{ $isMe ? 'rgba(0,228,118,0.04)' : '' }}';"
            style="{{ $isMe ? 'background:rgba(0,228,118,0.04);border-right:2px solid #00e476;' : '' }}">

            <td class="px-4 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                @if($i===0)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#D97706,#F5A623);color:#0a0a0a;box-shadow:0 0 10px rgba(245,166,35,0.3);">1</div>
                @elseif($i===1)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#64748B,#94A3B8);color:#0a0a0a;">2</div>
                @elseif($i===2)
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black"
                         style="background:linear-gradient(135deg,#7C2D12,#C2410C);color:#FED7AA;">3</div>
                @else
                    <span class="text-xs font-mono font-semibold" style="color:rgba(185,203,185,0.4);">{{ $i+1 }}</span>
                @endif
            </td>

            <td class="px-4 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black font-heading flex-shrink-0"
                         style="{{ $isMe ? 'background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;' : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#dde2f0;' }}">
                        {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold truncate" style="{{ $isMe ? 'color:#00e476;' : 'color:#dde2f0;' }}">{{ $u->name }}</span>
                            @if($isMe)
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(0,228,118,0.15);color:#00e476;">شما</span>
                            @endif
                        </div>
                        @if($u->department)
                        <p class="text-[11px] truncate mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $u->department }}</p>
                        @endif
                    </div>
                </div>
            </td>

            <td class="px-4 py-3.5 text-center hidden md:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <span class="font-mono text-xs font-semibold" style="color:rgba(185,203,185,0.7);">{{ $correct }} / {{ $total }}</span>
            </td>

            <td class="px-4 py-3.5 hidden sm:table-cell" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                <div class="flex items-center gap-2 justify-center">
                    <div class="w-20 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full"
                             style="width:{{ $accuracy }}%;background:{{ $accuracy >= 50 ? '#00e476' : ($accuracy >= 25 ? '#F5A623' : '#FF8A8A') }};"></div>
                    </div>
                    <span class="text-[11px] font-mono w-8 text-left" style="color:rgba(185,203,185,0.6);">{{ $accuracy }}%</span>
                </div>
            </td>

            <td class="px-4 py-3.5 text-center" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                @if($i===0)
                    <span class="text-xl font-black font-heading"
                          style="background:linear-gradient(90deg,#F5A623,#FFD700);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ $u->total_score }}</span>
                @elseif($i<3)
                    <span class="text-lg font-black font-heading" style="color:#94A3B8;">{{ $u->total_score }}</span>
                @else
                    <span class="font-bold text-white">{{ $u->total_score }}</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" class="px-4 py-16 text-center text-sm" style="color:rgba(185,203,185,0.4);">هیچ کاربری ثبت‌نام نکرده است.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- ── H2H Modal ── --}}
<div id="h2h-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="background:rgba(0,0,0,0.75);backdrop-filter:blur(10px);">
    <div class="liquid-glass rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col"
         style="border-color:rgba(0,228,118,0.2);">
        <div class="flex items-center justify-between px-6 py-4"
             style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div>
                <h2 class="font-black text-base font-heading text-white" id="h2h-title">مقایسه رو‌در‌رو</h2>
                <p class="text-xs mt-0.5" id="h2h-subtitle" style="color:rgba(185,203,185,0.6);"></p>
            </div>
            <button onclick="closeH2H()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center cursor-pointer"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
                <span class="material-symbols-outlined text-base" style="color:rgba(185,203,185,0.7);">close</span>
            </button>
        </div>
        <div class="px-6 py-4 grid grid-cols-3 gap-4 text-center"
             style="border-bottom:1px solid rgba(255,255,255,0.06);">
            <div>
                <p class="text-xs mb-1" style="color:rgba(185,203,185,0.6);">{{ auth()->user()->name }}</p>
                <p class="text-3xl font-black font-heading" id="my-score-display" style="color:#00e476;">—</p>
            </div>
            <div class="flex flex-col items-center justify-center gap-2">
                <p class="text-xs" style="color:rgba(185,203,185,0.4);">امتیاز</p>
                <span class="text-xs font-mono px-2 py-0.5 rounded-full" id="games-count"
                      style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">۰ بازی</span>
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
$jsPreds = [];
foreach($predictions as $userId => $gamePreds) {
    foreach($gamePreds as $pred) {
        $jsPreds[$userId][$pred->game_id] = ['h' => $pred->home_score, 'a' => $pred->away_score, 'pts' => $pred->points_earned];
    }
}
$jsGames = $finishedGames->map(fn($g) => [
    'id'   => $g->id,
    'home' => $g->homeTeam?->code ?? '?',
    'away' => $g->awayTeam?->code ?? '?',
    'rh'   => $g->home_score,
    'ra'   => $g->away_score,
    'date' => $g->scheduled_at?->format('j M'),
]);
@endphp

@push('scripts')
<script>
const ME_ID   = {{ auth()->id() }};
const ALL_PREDS = @json($jsPreds);
const ALL_GAMES = @json($jsGames);

document.getElementById('search-input').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#leaderboard-body .user-row').forEach(row => {
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none';
    });
});

function ptColor(pts) {
    if (pts >= 10) return '#00e476';
    if (pts >= 7)  return '#4D9FFF';
    if (pts >= 5)  return '#F5A623';
    if (pts >= 2)  return '#b9cbb9';
    return '#FF8A8A';
}

function openH2H(oppId, oppName) {
    document.getElementById('h2h-title').textContent    = oppId === ME_ID ? 'پیش‌بینی‌های من' : 'مقایسه رو‌در‌رو';
    document.getElementById('h2h-subtitle').textContent = oppId === ME_ID ? '' : '{{ auth()->user()->name }} در برابر ' + oppName;
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

function renderH2H(oppId) {
    const myP  = ALL_PREDS[ME_ID] || {};
    const oppP = ALL_PREDS[oppId] || {};
    let myTotal = 0, oppTotal = 0, rows = '', count = 0;

    ALL_GAMES.forEach(g => {
        const m = myP[g.id], o = oppP[g.id];
        if (!m && !o) return;
        myTotal  += m?.pts ?? 0;
        oppTotal += o?.pts ?? 0;
        count++;
        rows += `
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:12px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
            <div style="flex:1;text-align:center;">
                <div style="font-size:18px;font-weight:900;color:${ptColor(m?.pts??0)};">${m ? m.h+'–'+m.a : '—'}</div>
                ${m ? `<div style="font-size:10px;color:${ptColor(m.pts??0)};font-family:monospace;">+${m.pts??0}pt</div>` : ''}
            </div>
            <div style="text-align:center;flex-shrink:0;padding:0 8px;">
                <div style="font-size:10px;color:rgba(185,203,185,0.5);">${g.date}</div>
                <div style="font-size:13px;font-weight:700;color:#dde2f0;">${g.home} <span style="color:rgba(255,255,255,0.3);">vs</span> ${g.away}</div>
                <div style="font-size:11px;color:rgba(185,203,185,0.5);">نتیجه: <b style="color:#dde2f0;">${g.rh}–${g.ra}</b></div>
            </div>
            <div style="flex:1;text-align:center;">
                <div style="font-size:18px;font-weight:900;color:${ptColor(o?.pts??0)};">${o ? o.h+'–'+o.a : '—'}</div>
                ${o ? `<div style="font-size:10px;color:${ptColor(o.pts??0)};font-family:monospace;">+${o.pts??0}pt</div>` : ''}
            </div>
        </div>`;
    });

    document.getElementById('my-score-display').textContent  = myTotal;
    document.getElementById('opp-score-display').textContent = oppTotal;
    document.getElementById('games-count').textContent = count + ' بازی';
    document.getElementById('h2h-body').innerHTML = rows ||
        '<p style="text-align:center;color:rgba(185,203,185,0.5);padding:32px 0;">هنوز پیش‌بینی مشترکی وجود ندارد</p>';
}

document.getElementById('h2h-modal').addEventListener('click', function(e) {
    if (e.target === this) closeH2H();
});
</script>
@endpush
@endsection
