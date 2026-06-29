@extends('layouts.app')
@section('title', 'پیش‌بینی قهرمانی')

@section('content')

<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <h1 class="text-2xl font-black font-heading text-white flex items-center gap-3">
        <span class="material-symbols-outlined text-3xl" style="color:#FFD700;">emoji_events</span>
        پیش‌بینی قهرمانی
    </h1>
    <a href="{{ route('bracket') }}" class="inline-flex items-center gap-2 text-sm font-bold px-4 py-2 rounded-xl transition-all"
       style="background:rgba(255,255,255,0.06);color:rgba(185,203,185,0.8);border:1px solid rgba(255,255,255,0.1);">
        <span class="material-symbols-outlined text-base">account_tree</span>
        نمودار حذفی
    </a>
</div>

{{-- Points info --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    <div class="glass-card rounded-2xl p-4 text-center animate-slide-up stagger-1">
        <div class="text-2xl font-black font-heading" style="color:#FFD700;">۱۰۰</div>
        <div class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">امتیاز قهرمان</div>
        <div class="text-lg mt-1">🥇</div>
    </div>
    <div class="glass-card rounded-2xl p-4 text-center animate-slide-up stagger-2">
        <div class="text-2xl font-black font-heading" style="color:#C0C0C0;">۵۰</div>
        <div class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">امتیاز نایب‌قهرمان</div>
        <div class="text-lg mt-1">🥈</div>
    </div>
    <div class="glass-card rounded-2xl p-4 text-center animate-slide-up stagger-3">
        <div class="text-2xl font-black font-heading" style="color:#CD7F32;">۵۰</div>
        <div class="text-xs mt-1" style="color:rgba(185,203,185,0.6);">امتیاز تیم سوم</div>
        <div class="text-lg mt-1">🥉</div>
    </div>
</div>

@if($isLocked)
<div class="glass-card rounded-2xl px-5 py-4 mb-6 flex items-center gap-3" style="border:1px solid rgba(255,165,0,0.3);background:rgba(255,165,0,0.05);">
    <span class="material-symbols-outlined" style="color:#F5A623;">lock</span>
    <div>
        <p class="font-bold text-sm" style="color:#F5A623;">پیش‌بینی قفل شده است</p>
        <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.6);">پس از {{ $lockTime?->timezone('Asia/Tehran')->format('j M Y H:i') }} امکان تغییر وجود ندارد.</p>
    </div>
</div>
@endif

{{-- Current prediction display --}}
@if($prediction)
<div class="glass-card rounded-2xl p-5 mb-6 animate-slide-up" style="border:1px solid rgba(0,228,118,0.2);">
    <h3 class="text-sm font-black font-heading text-white mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-base" style="color:#00e476;">check_circle</span>
        پیش‌بینی فعلی شما
    </h3>
    <div class="grid grid-cols-3 gap-3">
        @foreach([['label'=>'قهرمان','team'=>$prediction->champion,'pts'=>$prediction->champion_points,'color'=>'#FFD700','icon'=>'🥇'],['label'=>'نایب‌قهرمان','team'=>$prediction->runnerUp,'pts'=>$prediction->runner_up_points,'color'=>'#C0C0C0','icon'=>'🥈'],['label'=>'تیم سوم','team'=>$prediction->thirdPlace,'pts'=>$prediction->third_place_points,'color'=>'#CD7F32','icon'=>'🥉']] as $slot)
        <div class="rounded-xl p-3 text-center" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
            <div class="text-lg mb-1">{{ $slot['icon'] }}</div>
            <p class="text-[10px] mb-2" style="color:rgba(185,203,185,0.5);">{{ $slot['label'] }}</p>
            @if($slot['team'])
                @if($slot['team']->flag_url)
                    <img src="{{ $slot['team']->flag_url }}" alt="" class="w-10 h-7 object-cover rounded mx-auto mb-1" onerror="this.style.display='none'">
                @endif
                <p class="text-xs font-bold" style="color:{{ $slot['color'] }};">{{ $slot['team']->name_fa ?? $slot['team']->name }}</p>
                @if($slot['pts'] > 0)
                    <p class="text-[10px] mt-1 font-bold" style="color:#00e476;">+{{ $slot['pts'] }} امتیاز</p>
                @endif
            @else
                <p class="text-xs" style="color:rgba(185,203,185,0.3);">—</p>
            @endif
        </div>
        @endforeach
    </div>
    @if($prediction->total_points > 0)
    <div class="mt-3 text-center">
        <span class="text-sm font-black font-heading" style="color:#00e476;">مجموع امتیاز قهرمانی: +{{ $prediction->total_points }}</span>
    </div>
    @endif
</div>
@endif

{{-- Actual results (if set by admin) --}}
@if($actualChampion || $actualRunnerUp || $actualThirdPlace)
<div class="glass-card rounded-2xl p-5 mb-6 animate-slide-up" style="border:1px solid rgba(255,215,0,0.2);background:linear-gradient(135deg,rgba(255,215,0,0.03),transparent);">
    <h3 class="text-sm font-black font-heading text-white mb-4 flex items-center gap-2">
        <span class="text-lg">🏆</span> نتیجه رسمی جام
    </h3>
    <div class="grid grid-cols-3 gap-3">
        @foreach([['label'=>'قهرمان','teamId'=>$actualChampion,'color'=>'#FFD700','icon'=>'🥇'],['label'=>'نایب‌قهرمان','teamId'=>$actualRunnerUp,'color'=>'#C0C0C0','icon'=>'🥈'],['label'=>'تیم سوم','teamId'=>$actualThirdPlace,'color'=>'#CD7F32','icon'=>'🥉']] as $slot)
        @php $t = $slot['teamId'] ? $teams->firstWhere('id', $slot['teamId']) : null; @endphp
        <div class="rounded-xl p-3 text-center" style="background:rgba(255,255,255,0.04);">
            <div class="text-lg mb-1">{{ $slot['icon'] }}</div>
            <p class="text-[10px] mb-1" style="color:rgba(185,203,185,0.5);">{{ $slot['label'] }}</p>
            @if($t)
                @if($t->flag_url)
                    <img src="{{ $t->flag_url }}" alt="" class="w-10 h-7 object-cover rounded mx-auto mb-1" onerror="this.style.display='none'">
                @endif
                <p class="text-xs font-bold" style="color:{{ $slot['color'] }};">{{ $t->name_fa ?? $t->name }}</p>
            @else
                <p class="text-xs" style="color:rgba(185,203,185,0.3);">اعلام نشده</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Form --}}
@if(!$isLocked)
<div class="glass-card rounded-3xl p-6 animate-slide-up" style="animation-delay:.2s">
    <h3 class="text-base font-black font-heading text-white mb-5 flex items-center gap-2">
        <span class="material-symbols-outlined" style="color:#00e476;">edit</span>
        {{ $prediction ? 'ویرایش پیش‌بینی' : 'ثبت پیش‌بینی' }}
    </h3>

    @if($errors->any())
    <div class="flex items-start gap-3 rounded-xl px-4 py-3 mb-5 flash-error">
        <span class="material-symbols-outlined text-base flex-shrink-0">error</span>
        <span class="text-sm">{{ $errors->first() }}</span>
    </div>
    @endif

    <form action="{{ route('tournament.prediction.store') }}" method="POST" x-data="tourForm()" class="space-y-6">
        @csrf

        @foreach([['field'=>'champion_team_id','label'=>'قهرمان','sub'=>'۱۰۰ امتیاز','color'=>'#FFD700','icon'=>'🥇'],['field'=>'runner_up_team_id','label'=>'نایب‌قهرمان','sub'=>'۵۰ امتیاز','color'=>'#C0C0C0','icon'=>'🥈'],['field'=>'third_place_team_id','label'=>'تیم سوم','sub'=>'۵۰ امتیاز','color'=>'#CD7F32','icon'=>'🥉']] as $slot)
        <div>
            <label class="flex items-center gap-2 text-sm font-bold mb-3" style="color:{{ $slot['color'] }};">
                <span>{{ $slot['icon'] }}</span>
                {{ $slot['label'] }} ({{ $slot['sub'] }})
            </label>

            {{-- Team search + select --}}
            <div x-data="teamPicker('{{ $slot['field'] }}', {{ $prediction?->{Str::before($slot['field'],'_team_id') . '_team_id'} ?? 'null' }})" class="relative">
                <input type="text" x-model="query" @input="filter()" @focus="open=true" @blur="setTimeout(()=>open=false,200)"
                       placeholder="جستجوی تیم..."
                       class="stitch-input w-full pr-4 pl-10"
                       style="font-size:14px;">
                <span class="material-symbols-outlined absolute left-3 top-3.5 text-base pointer-events-none" style="color:rgba(185,203,185,0.4);">search</span>

                <input type="hidden" name="{{ $slot['field'] }}" x-model="selected">

                {{-- Selected team display --}}
                <template x-if="selectedTeam">
                    <div class="flex items-center gap-2 mt-2 px-3 py-2 rounded-xl" style="background:rgba(0,228,118,0.06);border:1px solid rgba(0,228,118,0.2);">
                        <img :src="'/flags/' + selectedTeam.code + '.png'" :alt="selectedTeam.code" class="w-8 h-5 object-cover rounded" onerror="this.src=''" style="border:1px solid rgba(255,255,255,0.1);">
                        <span class="text-sm font-bold" style="color:#00e476;" x-text="selectedTeam.name_fa || selectedTeam.name"></span>
                        <button type="button" @click="clear()" class="mr-auto" style="color:rgba(185,203,185,0.5);">
                            <span class="material-symbols-outlined text-base">close</span>
                        </button>
                    </div>
                </template>

                {{-- Dropdown --}}
                <div x-show="open && filtered.length > 0" x-cloak
                     class="absolute top-full right-0 left-0 z-50 mt-1 rounded-xl overflow-hidden shadow-2xl"
                     style="background:#161c25;border:1px solid rgba(255,255,255,0.12);max-height:200px;overflow-y:auto;">
                    <template x-for="team in filtered" :key="team.id">
                        <button type="button" @click="select(team)"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-right transition-colors cursor-pointer"
                                style="color:rgba(185,203,185,0.8);"
                                onmouseover="this.style.background='rgba(0,228,118,0.08)';this.style.color='#00e476'"
                                onmouseout="this.style.background='';this.style.color='rgba(185,203,185,0.8)'">
                            <span class="text-xs font-bold font-mono w-8 flex-shrink-0" style="color:rgba(185,203,185,0.4);" x-text="team.code"></span>
                            <span class="text-sm font-semibold" x-text="team.name_fa || team.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn-primary w-full py-3.5 text-base">
            <span class="material-symbols-outlined">emoji_events</span>
            {{ $prediction ? 'ویرایش پیش‌بینی' : 'ثبت پیش‌بینی قهرمانی' }}
        </button>
    </form>
</div>
@endif

@endsection

@push('scripts')
<script>
const ALL_TEAMS = @json($teams->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'name_fa' => $t->name_fa, 'code' => $t->code, 'flag_url' => $t->flag_url]));

function teamPicker(field, initialId) {
    return {
        query: '',
        open: false,
        selected: initialId || '',
        selectedTeam: initialId ? ALL_TEAMS.find(t => t.id == initialId) : null,
        filtered: ALL_TEAMS,
        filter() {
            const q = this.query.toLowerCase();
            this.filtered = ALL_TEAMS.filter(t =>
                t.name.toLowerCase().includes(q) || (t.name_fa && t.name_fa.includes(q))
            );
        },
        select(team) {
            this.selected = team.id;
            this.selectedTeam = team;
            this.query = '';
            this.open = false;
        },
        clear() {
            this.selected = '';
            this.selectedTeam = null;
            this.query = '';
        }
    };
}

function tourForm() {
    return {};
}
</script>
@endpush
