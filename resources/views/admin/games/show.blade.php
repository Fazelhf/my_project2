@extends('layouts.admin')

@section('title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name)
@section('page-title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name)

@section('content')

@php
    $isFinished = $game->status === 'finished';
    if ($isFinished) {
        $stats = app(\App\Services\PredictionScoringService::class)->getGameStats($game);
        $predictions = $game->predictions()->with('user')->latest()->get();
    }
@endphp

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl text-sm font-bold" style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.25);color:#00e476;">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background:rgba(255,90,90,0.1);border:1px solid rgba(255,90,90,0.25);color:#FF8A8A;">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ── ستون چپ ── --}}
    <div class="space-y-4">

        {{-- کارت اطلاعات بازی --}}
        <div class="liquid-glass rounded-2xl p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-black mx-auto mb-1"
                         style="background:rgba(0,228,118,0.1);color:#00e476;">
                        {{ $game->homeTeam->code }}
                    </div>
                    <p class="font-bold text-sm text-white">{{ $game->homeTeam->name }}</p>
                </div>
                <div class="text-center px-3">
                    @if($isFinished)
                        <p class="text-3xl font-black font-heading" style="color:#00e476;">
                            {{ $game->home_score }}–{{ $game->away_score }}
                        </p>
                        <p class="text-xs mt-1" style="color:rgba(185,203,185,0.5);">نتیجه نهایی ۹۰'</p>
                        @if($game->home_score_final !== null)
                            <p class="text-xs mt-0.5 font-mono" style="color:#4D9FFF;">
                                ({{ $game->home_score_final }}–{{ $game->away_score_final }} نهایی)
                            </p>
                        @endif
                    @else
                        <p class="text-xl font-bold" style="color:rgba(255,255,255,0.3);">vs</p>
                    @endif
                </div>
                <div class="text-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-black mx-auto mb-1"
                         style="background:rgba(77,159,255,0.1);color:#4D9FFF;">
                        {{ $game->awayTeam->code }}
                    </div>
                    <p class="font-bold text-sm text-white">{{ $game->awayTeam->name }}</p>
                </div>
            </div>
            <div class="pt-3 flex flex-wrap gap-2 text-xs" style="border-top:1px solid rgba(255,255,255,0.07);">
                <span class="px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">{{ $game->stage_label }}</span>
                @if($game->scheduled_at)
                    <span class="px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">
                        {{ $game->scheduled_at->timezone('Asia/Tehran')->format('j M Y H:i') }}
                    </span>
                @endif
                @if($game->venue)
                    <span class="px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.7);">{{ $game->venue }}</span>
                @endif
                @if($isFinished)
                    <span class="px-2 py-1 rounded-lg font-bold" style="background:rgba(0,228,118,0.1);color:#00e476;">پایان یافته</span>
                @else
                    <span class="px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.5);">پیش‌رو</span>
                @endif
            </div>
        </div>

        {{-- گل‌های بازی --}}
        @php
            $goalsData = is_array($game->goals) ? $game->goals : (is_string($game->goals) ? json_decode($game->goals, true) : null);
            $homeGoals = $goalsData['home'] ?? [];
            $awayGoals = $goalsData['away'] ?? [];
        @endphp
        @if(!empty($homeGoals) || !empty($awayGoals))
        <div class="liquid-glass rounded-2xl p-4">
            <p class="text-xs font-bold mb-3" style="color:rgba(185,203,185,0.5);">گل‌های بازی</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="space-y-1">
                    <p class="font-bold text-white mb-1.5 text-[11px]">{{ $game->homeTeam->name }}</p>
                    @forelse($homeGoals as $g)
                        <div class="flex items-center gap-1.5" style="color:rgba(185,203,185,0.8);">
                            <span style="color:#00e476;">⚽</span>
                            <span>{{ $g['scorer'] ?? $g['name'] ?? '?' }}</span>
                            @if(!empty($g['minute']))
                                <span class="font-mono text-[10px]" style="color:rgba(185,203,185,0.4);">{{ $g['minute'] }}'</span>
                            @endif
                            @if(!empty($g['type']) && $g['type'] !== 'goal')
                                <span class="text-[10px]" style="color:#F59E0B;">({{ $g['type'] }})</span>
                            @endif
                        </div>
                    @empty
                        <p style="color:rgba(185,203,185,0.3);">—</p>
                    @endforelse
                </div>
                <div class="space-y-1">
                    <p class="font-bold text-white mb-1.5 text-[11px]">{{ $game->awayTeam->name }}</p>
                    @forelse($awayGoals as $g)
                        <div class="flex items-center gap-1.5" style="color:rgba(185,203,185,0.8);">
                            <span style="color:#00e476;">⚽</span>
                            <span>{{ $g['scorer'] ?? $g['name'] ?? '?' }}</span>
                            @if(!empty($g['minute']))
                                <span class="font-mono text-[10px]" style="color:rgba(185,203,185,0.4);">{{ $g['minute'] }}'</span>
                            @endif
                            @if(!empty($g['type']) && $g['type'] !== 'goal')
                                <span class="text-[10px]" style="color:#F59E0B;">({{ $g['type'] }})</span>
                            @endif
                        </div>
                    @empty
                        <p style="color:rgba(185,203,185,0.3);">—</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        {{-- ثبت / ویرایش نتیجه --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="material-symbols-outlined text-sm" style="color:#00e476;">{{ $isFinished ? 'edit' : 'check_circle' }}</span>
                <span class="text-xs font-bold text-white">{{ $isFinished ? 'ویرایش نتیجه' : 'ثبت نتیجه' }}</span>
            </div>
            <form method="POST"
                  action="{{ $isFinished ? route('admin.games.update-result', $game) : route('admin.games.result', $game) }}"
                  class="p-4 space-y-4">
                @csrf
                @if($errors->any())
                    <div class="px-3 py-2 rounded-xl text-xs" style="background:rgba(255,90,90,0.1);border:1px solid rgba(255,90,90,0.25);color:#FF8A8A;">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                @endif

                <div>
                    <label class="text-xs mb-2 block" style="color:rgba(185,203,185,0.6);">نتیجه ۹۰ دقیقه</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <label class="text-xs text-center block mb-1" style="color:rgba(185,203,185,0.5);">{{ $game->homeTeam->name }}</label>
                            <input type="number" name="home_score"
                                   value="{{ old('home_score', $isFinished ? $game->home_score : 0) }}"
                                   min="0" max="99" required
                                   class="stitch-input text-center text-xl font-black w-full">
                        </div>
                        <span class="text-2xl font-bold flex-shrink-0" style="color:rgba(255,255,255,0.3);">–</span>
                        <div class="flex-1">
                            <label class="text-xs text-center block mb-1" style="color:rgba(185,203,185,0.5);">{{ $game->awayTeam->name }}</label>
                            <input type="number" name="away_score"
                                   value="{{ old('away_score', $isFinished ? $game->away_score : 0) }}"
                                   min="0" max="99" required
                                   class="stitch-input text-center text-xl font-black w-full">
                        </div>
                    </div>
                </div>

                @if($game->isKnockout())
                <div>
                    <label class="text-xs mb-2 block" style="color:rgba(77,159,255,0.7);">نتیجه نهایی (اضافه/پنالتی — اختیاری)</label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="home_score_final"
                               value="{{ old('home_score_final', $game->home_score_final) }}"
                               min="0" max="99" placeholder="–"
                               class="stitch-input text-center text-sm w-full">
                        <span class="text-lg flex-shrink-0" style="color:rgba(255,255,255,0.3);">–</span>
                        <input type="number" name="away_score_final"
                               value="{{ old('away_score_final', $game->away_score_final) }}"
                               min="0" max="99" placeholder="–"
                               class="stitch-input text-center text-sm w-full">
                    </div>
                </div>
                @endif

                <button type="submit" class="btn-primary text-sm py-3 w-full">
                    {{ $isFinished ? 'ذخیره تغییر نتیجه + بازمحاسبه امتیازات' : 'ثبت نتیجه و محاسبه امتیازات' }}
                </button>

                @if($isFinished)
                <p class="text-xs text-center" style="color:rgba(185,203,185,0.4);">بعد از ذخیره، امتیاز همه پیش‌بینی‌ها خودکار بازمحاسبه می‌شود</p>
                @endif
            </form>
        </div>

        @if($isFinished)
        {{-- آمار امتیازدهی --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="text-xs font-bold text-white">آمار امتیازدهی</span>
                <span class="text-xs mr-2" style="color:rgba(185,203,185,0.5);">{{ $stats['scored'] }} از {{ $stats['total'] }} ارزیابی شد</span>
            </div>
            <div class="p-4 grid grid-cols-5 gap-2 text-center">
                @foreach([10=>'#00e476', 7=>'#4D9FFF', 5=>'#A78BFA', 2=>'rgba(185,203,185,0.5)', 0=>'rgba(185,203,185,0.25)'] as $pts => $color)
                <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.04);">
                    <p class="text-lg font-black font-heading" style="color:{{ $color }};">{{ $stats['breakdown'][$pts] ?? 0 }}</p>
                    <p class="text-xs mt-1" style="color:rgba(185,203,185,0.4);">+{{ $pts }}</p>
                </div>
                @endforeach
            </div>
            @if($stats['avg'] > 0)
            <div class="px-4 pb-4 text-center">
                <span class="text-xs" style="color:rgba(185,203,185,0.5);">میانگین امتیاز:</span>
                <span class="text-sm font-bold" style="color:#00e476;">{{ $stats['avg'] }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.games.edit', $game) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all"
               style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.25);color:#00e476;"
               onmouseover="this.style.background='rgba(0,228,118,0.15)';this.style.borderColor='rgba(0,228,118,0.35)'"
               onmouseout="this.style.background='rgba(0,228,118,0.1)';this.style.borderColor='rgba(0,228,118,0.25)'">
                <span class="material-symbols-outlined text-sm">edit</span>
                ویرایش بازی
            </a>
            <form id="deleteForm" method="POST" action="{{ route('admin.games.destroy', $game) }}" style="display:inline;">
                @csrf @method('DELETE')
                <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all"
                        style="background:rgba(255,90,90,0.1);border:1px solid rgba(255,90,90,0.25);color:#FF8A8A;"
                        onmouseover="this.style.background='rgba(255,90,90,0.15)';this.style.borderColor='rgba(255,90,90,0.35)'"
                        onmouseout="this.style.background='rgba(255,90,90,0.1)';this.style.borderColor='rgba(255,90,90,0.25)'">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    حذف
                </button>
            </form>
            <a href="{{ route('admin.games.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium transition-colors"
               style="color:rgba(185,203,185,0.5);"
               onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(185,203,185,0.5)'">
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                بازگشت به لیست بازی‌ها
            </a>
        </div>

        <script>
        function confirmDelete() {
            if (confirm('آیا مطمئن هستی که می‌خوای این بازی را حذف کنی؟\n\nاین عملیات قابل بازگشت نیست!')) {
                document.getElementById('deleteForm').submit();
            }
        }
        </script>
    </div>

    {{-- ── ستون راست: پیش‌بینی‌ها ── --}}
    @if($isFinished && $predictions->count() > 0)
    <div class="xl:col-span-2">
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-base" style="color:#4D9FFF;">people</span>
                    <h3 class="font-black text-sm font-heading text-white">پیش‌بینی‌های کاربران</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full font-mono" style="background:rgba(77,159,255,0.12);color:#4D9FFF;">{{ $predictions->count() }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.02);border-bottom:1px solid rgba(255,255,255,0.07);">
                            <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.5);">کاربر</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">پیش‌بینی</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">امتیاز</th>
                            <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predictions as $pred)
                        @php
                            $eff  = $pred->points_override ?? $pred->points_earned;
                            $color = match(true) {
                                $eff >= 10 => '#00e476',
                                $eff >= 7  => '#4D9FFF',
                                $eff >= 5  => '#A78BFA',
                                $eff >= 2  => 'rgba(185,203,185,0.6)',
                                default    => 'rgba(185,203,185,0.3)',
                            };
                        @endphp
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);"
                            onmouseover="this.style.background='rgba(0,228,118,0.02)'"
                            onmouseout="this.style.background=''">
                            <td class="px-4 py-3">
                                <span class="font-medium text-white text-xs">{{ $pred->user->name }}</span>
                                @if($pred->is_admin_edited)
                                    <span class="mr-1 text-xs px-1.5 py-0.5 rounded font-bold" style="background:rgba(245,158,11,0.1);color:#F59E0B;font-size:10px;">ویرایش‌شده</span>
                                @endif
                                @if($pred->points_override !== null)
                                    <span class="mr-1 text-xs px-1.5 py-0.5 rounded font-bold" style="background:rgba(167,139,250,0.1);color:#A78BFA;font-size:10px;">override</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-white">
                                {{ $pred->home_score }}–{{ $pred->away_score }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-black font-heading" style="color:{{ $color }};">
                                    {{ $eff !== null ? '+' . $eff : '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button"
                                        onclick="openEditModal({{ $pred->id }}, '{{ $pred->user->name }}', {{ $pred->home_score }}, {{ $pred->away_score }}, {{ $eff ?? 'null' }})"
                                        class="text-xs font-bold px-3 py-1 rounded-lg transition-all"
                                        style="background:rgba(77,159,255,0.1);border:1px solid rgba(77,159,255,0.25);color:#4D9FFF;"
                                        onmouseover="this.style.background='rgba(77,159,255,0.2)'"
                                        onmouseout="this.style.background='rgba(77,159,255,0.1)'">
                                    ویرایش
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal ویرایش پیش‌بینی --}}
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center"
         style="background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);">
        <div class="liquid-glass rounded-2xl w-full max-w-sm mx-4 overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div>
                    <h3 class="font-black text-sm font-heading text-white">ویرایش پیش‌بینی</h3>
                    <p class="text-xs mt-0.5" id="modalUserName" style="color:rgba(185,203,185,0.5);"></p>
                </div>
                <button onclick="closeModal()" class="text-xs" style="color:rgba(185,203,185,0.5);">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-5 space-y-4">

                {{-- ویرایش نتیجه پیش‌بینی --}}
                <form id="editForm" method="POST" class="space-y-3">
                    @csrf
                    <label class="text-xs font-bold block" style="color:rgba(185,203,185,0.6);">نتیجه پیش‌بینی‌شده</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="modalHome" name="home_score" min="0" max="99" required
                               class="stitch-input text-center text-xl font-black flex-1">
                        <span class="text-xl font-bold flex-shrink-0" style="color:rgba(255,255,255,0.3);">–</span>
                        <input type="number" id="modalAway" name="away_score" min="0" max="99" required
                               class="stitch-input text-center text-xl font-black flex-1">
                    </div>
                    <input type="text" name="admin_note" placeholder="دلیل ویرایش (الزامی)" required
                           class="stitch-input text-xs w-full">
                    <button type="submit" class="btn-primary text-xs py-2.5 w-full">ذخیره پیش‌بینی</button>
                </form>

                <hr style="border-color:rgba(255,255,255,0.07);">

                {{-- Override امتیاز --}}
                <form id="overrideForm" method="POST" class="space-y-3">
                    @csrf
                    <label class="text-xs font-bold block" style="color:rgba(167,139,250,0.8);">Override امتیاز (اختیاری)</label>
                    <div class="flex gap-2">
                        <input type="number" id="modalPts" name="points_override" min="0" max="100"
                               placeholder="امتیاز دلخواه"
                               class="stitch-input text-xs flex-1">
                        <button type="button" onclick="clearOverride()"
                                class="text-xs px-3 rounded-lg flex-shrink-0"
                                style="background:rgba(255,90,90,0.1);border:1px solid rgba(255,90,90,0.2);color:#FF8A8A;">
                            پاک
                        </button>
                    </div>
                    <input type="text" name="admin_note" placeholder="دلیل override (الزامی)" required
                           class="stitch-input text-xs w-full">
                    <button type="submit" class="text-xs py-2.5 w-full rounded-xl font-bold"
                            style="background:rgba(167,139,250,0.1);border:1px solid rgba(167,139,250,0.3);color:#A78BFA;">
                        اعمال Override
                    </button>
                </form>
            </div>
        </div>
    </div>

    @elseif(!$isFinished)
    {{-- پیش‌بینی‌ها هنوز قابل مشاهده نیستند --}}
    <div class="xl:col-span-2 flex items-center justify-center">
        <div class="text-center" style="color:rgba(185,203,185,0.3);">
            <span class="material-symbols-outlined text-5xl">sports_soccer</span>
            <p class="text-sm mt-2">بعد از ثبت نتیجه، لیست پیش‌بینی‌ها اینجا نمایش داده می‌شود</p>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
function openEditModal(predId, userName, home, away, pts) {
    document.getElementById('modalUserName').textContent = userName;
    document.getElementById('modalHome').value = home;
    document.getElementById('modalAway').value = away;
    document.getElementById('modalPts').value  = pts ?? '';

    document.getElementById('editForm').action     = '/admin/predictions/' + predId + '/edit';
    document.getElementById('overrideForm').action = '/admin/predictions/' + predId + '/points-override';

    const modal = document.getElementById('editModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function clearOverride() {
    document.getElementById('modalPts').value = '';
}

document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush

@endsection
