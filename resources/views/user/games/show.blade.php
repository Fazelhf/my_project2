@extends('layouts.app')

@section('title', ($game->homeTeam->name_fa ?? $game->homeTeam->name) . ' vs ' . ($game->awayTeam->name_fa ?? $game->awayTeam->name))

@section('content')

@php
    $prediction = $myPrediction ?? null;
    $stageLabels = [
        'group'         => 'مرحله گروهی',
        'round_of_32'   => 'دور ۳۲',
        'round_of_16'   => 'دور ۱۶',
        'quarter_final' => 'ربع‌نهایی',
        'semi_final'    => 'نیمه‌نهایی',
        'third_place'   => 'رده‌بندی سوم',
        'final'         => 'فینال',
    ];
    $stageLabel = $stageLabels[$game->stage] ?? $game->stage;
    $goalsRaw = $game->goals;
    if (is_string($goalsRaw)) $goalsRaw = json_decode($goalsRaw, true);
    if (isset($goalsRaw['home'])) {
        $homeGoals = collect($goalsRaw['home']);
        $awayGoals = collect($goalsRaw['away'] ?? []);
        $goals = $homeGoals->map(fn($g) => array_merge($g, ['team'=>'home']))
                  ->concat($awayGoals->map(fn($g) => array_merge($g, ['team'=>'away'])));
    } else {
        $goals = collect($goalsRaw ?? []);
        $homeGoals = $goals->filter(fn($g) => ($g['team'] ?? $g['side'] ?? '') === 'home');
        $awayGoals = $goals->filter(fn($g) => ($g['team'] ?? $g['side'] ?? '') === 'away');
    }
@endphp

{{-- Back button --}}
<div class="mb-5">
    <a href="{{ route('games.index') }}" class="inline-flex items-center gap-2 text-sm font-bold transition-colors" style="color:rgba(185,203,185,0.7);"
       onmouseover="this.style.color='#00e476'" onmouseout="this.style.color='rgba(185,203,185,0.7)'">
        <span class="material-symbols-outlined text-base">arrow_forward</span>
        بازگشت به بازی‌ها
    </a>
</div>

{{-- Match header card --}}
<div class="glass-card rounded-3xl overflow-hidden mb-5 animate-slide-up">

    {{-- Stage + meta bar --}}
    <div class="px-6 pt-5 pb-3 flex items-center justify-between flex-wrap gap-2"
         style="border-bottom:1px solid rgba(255,255,255,0.06);">
        <div class="flex items-center gap-3">
            @if($game->match_number)
                <span class="text-xs font-mono px-2.5 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">بازی #{{ $game->match_number }}</span>
            @endif
            <span class="text-xs font-bold px-2.5 py-1 rounded-lg" style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">{{ $stageLabel }}</span>
            @if($game->group_name)
                <span class="text-xs font-mono px-2.5 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">گروه {{ $game->group_name }}</span>
            @endif
        </div>
        <div class="text-xs font-mono" style="color:rgba(185,203,185,0.5);">
            {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M Y • H:i') }} تهران
        </div>
    </div>

    {{-- Teams + Score --}}
    <div class="px-6 py-8">
        <div class="flex items-center justify-between gap-4">

            {{-- Home team --}}
            <div class="flex-1 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl mb-3 flex items-center justify-center overflow-hidden"
                     style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
                    @if($game->homeTeam->flag_url)
                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <span class="text-base font-black font-heading hidden w-full h-full items-center justify-center" style="color:#F0F4FF;">{{ $game->homeTeam->code }}</span>
                    @else
                        <span class="text-base font-black font-heading" style="color:#F0F4FF;">{{ $game->homeTeam->code }}</span>
                    @endif
                </div>
                <p class="text-base font-black font-heading text-white">{{ $game->homeTeam->name_fa ?? $game->homeTeam->name }}</p>
                @if($game->homeTeam->name_fa)
                    <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $game->homeTeam->name }}</p>
                @endif
            </div>

            {{-- Score --}}
            <div class="flex-shrink-0 text-center px-4">
                @if($game->status === 'finished')
                    <div class="text-5xl font-black font-heading mb-2" style="color:#00e476;">
                        {{ $game->home_score }} – {{ $game->away_score }}
                    </div>
                    @if($game->home_score_ht !== null)
                        <div class="text-xs font-mono mb-2" style="color:rgba(185,203,185,0.5);">
                            نیمه اول: {{ $game->home_score_ht }}–{{ $game->away_score_ht }}
                        </div>
                    @endif
                    @if($game->home_score_final !== null && ($game->home_score_final !== $game->home_score || $game->away_score_final !== $game->away_score))
                        <div class="text-xs font-mono mb-2" style="color:rgba(77,159,255,0.8);">
                            نهایی (وقت اضافه): {{ $game->home_score_final }}–{{ $game->away_score_final }}
                        </div>
                    @endif
                    <span class="text-xs px-3 py-1 rounded-full font-bold" style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.25);">پایان یافت</span>
                @elseif($game->status === 'live')
                    <div class="text-4xl font-black font-heading mb-2" style="color:#4D9FFF;">
                        {{ $game->home_score ?? '?' }} – {{ $game->away_score ?? '?' }}
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full font-bold flex items-center gap-1 justify-center"
                          style="background:rgba(77,159,255,0.1);color:#4D9FFF;border:1px solid rgba(77,159,255,0.25);">
                        <span class="w-2 h-2 rounded-full animate-pulse" style="background:#4D9FFF;"></span>
                        زنده
                    </span>
                @else
                    <div class="text-3xl font-black font-heading mb-2" style="color:rgba(255,255,255,0.2);">vs</div>
                    @if($game->scheduled_at)
                        <div class="text-sm font-mono" style="color:rgba(185,203,185,0.6);">{{ $game->scheduled_at->timezone('Asia/Tehran')->format('H:i') }}</div>
                    @endif
                    <span class="text-xs px-3 py-1 rounded-full font-bold mt-2 inline-block" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">پیش‌رو</span>
                @endif
            </div>

            {{-- Away team --}}
            <div class="flex-1 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl mb-3 flex items-center justify-center overflow-hidden"
                     style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);">
                    @if($game->awayTeam->flag_url)
                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}"
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <span class="text-base font-black font-heading hidden w-full h-full items-center justify-center" style="color:#F0F4FF;">{{ $game->awayTeam->code }}</span>
                    @else
                        <span class="text-base font-black font-heading" style="color:#F0F4FF;">{{ $game->awayTeam->code }}</span>
                    @endif
                </div>
                <p class="text-base font-black font-heading text-white">{{ $game->awayTeam->name_fa ?? $game->awayTeam->name }}</p>
                @if($game->awayTeam->name_fa)
                    <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $game->awayTeam->name }}</p>
                @endif
            </div>

        </div>

        {{-- Venue --}}
        @if($game->venue)
            <div class="mt-5 flex items-center justify-center gap-2 text-xs" style="color:rgba(185,203,185,0.5);">
                <span class="material-symbols-outlined text-sm">location_on</span>
                {{ $game->venue }}
            </div>
        @endif
    </div>

    {{-- My prediction --}}
    @if($prediction)
    <div class="px-6 pb-5">
        <div class="rounded-2xl px-5 py-3.5 flex items-center justify-between"
             style="background:rgba(0,229,160,0.06);border:1px solid rgba(0,229,160,0.18);">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">person</span>
                <span class="text-sm font-bold" style="color:#00e476;">پیش‌بینی من</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-lg font-black font-heading text-white">{{ $prediction->home_score }}–{{ $prediction->away_score }}</span>
                @if($prediction->points_earned !== null)
                    @php
                        $pts = $prediction->points_earned;
                        $ptClass = $pts >= 10 ? 'badge-green' : ($pts >= 7 ? 'badge-blue' : ($pts >= 5 ? 'badge-gold' : ($pts >= 2 ? 'badge-gray' : 'badge-red')));
                    @endphp
                    <span class="badge {{ $ptClass }}">+{{ $pts }} امتیاز</span>
                @elseif($game->status !== 'finished')
                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.6);">در انتظار نتیجه</span>
                @endif
            </div>
        </div>
    </div>
    @elseif($game->status !== 'finished' && !$game->isPredictionLocked())
    <div class="px-6 pb-5">
        <a href="{{ route('games.index') }}"
           class="flex items-center justify-center gap-2 py-3 rounded-2xl text-sm font-bold transition-all"
           style="background:#00e476;color:#003919;">
            <span class="material-symbols-outlined text-base">add</span>
            ثبت پیش‌بینی
        </a>
    </div>
    @endif

</div>

{{-- Goals section --}}
@if($game->status === 'finished' && count($goals) > 0)
<div class="glass-card rounded-3xl p-6 mb-5 animate-slide-up" style="animation-delay:.1s">
    <h3 class="text-sm font-black font-heading text-white mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-base" style="color:#00e476;">sports_soccer</span>
        گل‌ها
    </h3>
    <div class="space-y-2">
        @foreach(collect($goals)->sortBy('minute') as $goal)
        @php
            $isHome = ($goal['team'] ?? $goal['side'] ?? 'home') === 'home';
            $isOG   = ($goal['owngoal'] ?? false);
            $isPen  = ($goal['penalty'] ?? false);
        @endphp
        <div class="flex items-center gap-3 py-2 px-3 rounded-xl" style="background:rgba(255,255,255,0.03);">
            @if($isHome)
                <div class="flex-1 flex items-center gap-2">
                    @if($game->homeTeam->flag_url)
                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->code }}" class="w-5 h-3.5 object-cover rounded" style="border:1px solid rgba(255,255,255,0.1);" onerror="this.style.display='none'">
                    @endif
                    <span class="text-sm font-semibold text-white">{{ $goal['name'] ?? '—' }}</span>
                    @if($isOG)<span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(255,90,90,0.15);color:#FF8A8A;">OG</span>@endif
                    @if($isPen)<span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(77,159,255,0.15);color:#4D9FFF;">P</span>@endif
                </div>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg" style="background:rgba(0,228,118,0.1);color:#00e476;">{{ $goal['minute'] ?? '' }}'</span>
                <div class="w-24 text-right"></div>
            @else
                <div class="w-24"></div>
                <span class="text-xs font-mono font-bold px-2 py-0.5 rounded-lg" style="background:rgba(77,159,255,0.1);color:#4D9FFF;">{{ $goal['minute'] ?? '' }}'</span>
                <div class="flex-1 flex items-center gap-2 justify-end">
                    @if($isOG)<span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(255,90,90,0.15);color:#FF8A8A;">OG</span>@endif
                    @if($isPen)<span class="text-[10px] px-1.5 py-0.5 rounded font-bold" style="background:rgba(77,159,255,0.15);color:#4D9FFF;">P</span>@endif
                    <span class="text-sm font-semibold text-white">{{ $goal['name'] ?? '—' }}</span>
                    @if($game->awayTeam->flag_url)
                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->code }}" class="w-5 h-3.5 object-cover rounded" style="border:1px solid rgba(255,255,255,0.1);" onerror="this.style.display='none'">
                    @endif
                </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- All predictions for this game --}}
@php
    $allPreds = $game->predictions ?? collect();
@endphp
@if($game->status === 'finished' && $allPreds->count() > 0)
<div class="glass-card rounded-3xl p-6 animate-slide-up" style="animation-delay:.2s">
    <h3 class="text-sm font-black font-heading text-white mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-base" style="color:#4D9FFF;">groups</span>
        پیش‌بینی همه ({{ $allPreds->count() }} نفر)
    </h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
        @foreach($allPreds->sortByDesc('points_earned') as $pred)
        @php
            $isMe  = $pred->user_id === auth()->id();
            $pts   = $pred->points_earned;
            $color = $pts >= 10 ? '#00E5A0' : ($pts >= 7 ? '#4D9FFF' : ($pts >= 5 ? '#F5A623' : ($pts >= 2 ? '#8BA0C4' : '#FF5A5A')));
        @endphp
        <div class="rounded-xl px-3 py-2.5 flex items-center justify-between gap-2"
             style="background:{{ $isMe ? 'rgba(0,229,160,0.06)' : 'rgba(255,255,255,0.03)' }};border:1px solid {{ $isMe ? 'rgba(0,229,160,0.2)' : 'rgba(255,255,255,0.07)' }};">
            <div class="flex items-center gap-1.5 min-w-0">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black font-heading flex-shrink-0"
                     style="{{ $isMe ? 'background:linear-gradient(135deg,#00BF85,#00E5A0);color:#0a0a0a;' : 'background:rgba(255,255,255,0.08);color:#F0F4FF;' }}">
                    {{ mb_strtoupper(mb_substr($pred->user->name, 0, 1, 'UTF-8')) }}
                </div>
                <span class="text-[10px] font-semibold truncate" style="{{ $isMe ? 'color:#00E5A0;' : 'color:#8BA0C4;' }}">
                    {{ $isMe ? 'من' : $pred->user->name }}
                </span>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="text-sm font-black font-heading" style="color:{{ $color }};">{{ $pred->home_score }}–{{ $pred->away_score }}</span>
                @if($pts !== null)
                    <div class="text-[9px] font-bold" style="color:{{ $color }};">+{{ $pts }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Share prediction button --}}
@if($prediction)
<div class="glass-card rounded-3xl p-5 mb-5 animate-slide-up" style="animation-delay:.25s">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <p class="text-sm font-bold text-white">اشتراک‌گذاری پیش‌بینی</p>
            <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">پیش‌بینی خود را با دیگران به اشتراک بگذارید</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="copyShareLink()" id="copy-btn"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold cursor-pointer transition-all"
                    style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.25);">
                <span class="material-symbols-outlined text-base">link</span>
                کپی لینک
            </button>
            <a href="{{ route('prediction.share', ['game' => $game->id, 'user' => auth()->id()]) }}" target="_blank"
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold cursor-pointer transition-all"
               style="background:rgba(77,159,255,0.1);color:#4D9FFF;border:1px solid rgba(77,159,255,0.25);">
                <span class="material-symbols-outlined text-base">open_in_new</span>
                مشاهده کارت
            </a>
        </div>
    </div>
</div>
@endif

{{-- Comments section --}}
<div class="glass-card rounded-3xl p-6 animate-slide-up" style="animation-delay:.3s" x-data="commentsApp()" x-init="init()">
    <h3 class="text-sm font-black font-heading text-white mb-5 flex items-center gap-2">
        <span class="material-symbols-outlined text-base" style="color:#4D9FFF;">chat_bubble</span>
        نظرات
        <span class="text-xs px-2 py-0.5 rounded-full font-mono" style="background:rgba(77,159,255,0.1);color:#4D9FFF;">{{ $comments->count() }}</span>
    </h3>

    {{-- Comment form --}}
    <form action="{{ route('games.comments.store', $game) }}" method="POST" class="mb-6">
        @csrf
        <div class="flex gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black flex-shrink-0"
                 style="background:linear-gradient(135deg,#00b85e,#00e476);color:#003919;">
                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1, 'UTF-8')) }}
            </div>
            <div class="flex-1">
                <textarea name="body" rows="2" maxlength="1000" required
                          placeholder="نظر خود را بنویسید..."
                          class="stitch-input w-full resize-none text-sm"
                          style="padding:12px 16px;min-height:80px;"></textarea>
                <button type="submit" class="mt-2 flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold cursor-pointer transition-all"
                        style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">
                    <span class="material-symbols-outlined text-base">send</span>
                    ثبت نظر
                </button>
            </div>
        </div>
    </form>

    {{-- Comments list --}}
    <div class="space-y-4" id="comments-list">
        @foreach($comments as $comment)
        @if(!$comment->parent_id)
        @include('user.games._comment', ['comment' => $comment, 'game' => $game])
        @endif
        @endforeach

        @if($comments->isEmpty())
        <p class="text-sm text-center py-6" style="color:rgba(185,203,185,0.4);">اولین نفری باشید که نظر می‌دهید!</p>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
function commentsApp() {
    return { init() {} };
}

function copyShareLink() {
    const url = '{{ route('prediction.share', ['game' => $game->id, 'user' => auth()->id()]) }}';
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('copy-btn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-base">check_circle</span> کپی شد!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}

async function likeComment(id, btn) {
    const resp = await fetch(`/comments/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        }
    });
    const data = await resp.json();
    const icon = btn.querySelector('.like-icon');
    const cnt = btn.querySelector('.like-count');
    if (icon) icon.style.color = data.liked ? '#ff5a8a' : 'rgba(185,203,185,0.5)';
    if (cnt) cnt.textContent = data.count > 0 ? data.count : '';
}
</script>
@endpush
