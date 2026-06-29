@extends('layouts.admin')
@section('title', 'قوانین امتیازدهی')
@section('page-title', 'قوانین امتیازدهی بازی‌ها')

@section('content')

{{-- راهنما --}}
<div class="liquid-glass rounded-2xl p-4 mb-5 flex items-start gap-3" style="border-right:3px solid rgba(77,159,255,0.4);">
    <span class="material-symbols-outlined text-lg flex-shrink-0 mt-0.5" style="color:#4D9FFF;">info</span>
    <div class="text-xs space-y-1" style="color:rgba(185,203,185,0.7);">
        <p><strong class="text-white">پیش‌فرض سیستم:</strong> دقیق=۱۰ | اختلاف گل=۷ | روند=۵ | شرکت=۲ | ضریب=۱×</p>
        <p>برای هر بازی می‌توانید قانون مجزا تعریف کنید. <strong class="text-white">بازی‌های فینال</strong> با ضریب ۲ → پیش‌بینی دقیق = ۲۰ امتیاز.</p>
        <p>اگر قانونی تعریف نشده باشد، سیستم از مقادیر پیش‌فرض استفاده می‌کند.</p>
    </div>
</div>

{{-- فیلتر --}}
<div class="liquid-glass rounded-2xl px-5 py-3 mb-4 flex flex-wrap gap-3 items-center">
    <form method="GET" class="flex gap-3 flex-wrap">
        <select name="stage" class="stitch-input text-xs" style="width:160px;" onchange="this.form.submit()">
            <option value="">همه مراحل</option>
            @foreach($stages as $key => $label)
            <option value="{{ $key }}" {{ request('stage')===$key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <select name="has_rule" class="stitch-input text-xs" style="width:160px;" onchange="this.form.submit()">
            <option value="">همه بازی‌ها</option>
            <option value="yes" {{ request('has_rule')==='yes' ? 'selected' : '' }}>دارای قانون سفارشی</option>
            <option value="no" {{ request('has_rule')==='no' ? 'selected' : '' }}>قانون پیش‌فرض</option>
        </select>
    </form>
</div>

<div class="space-y-3">
    @foreach($games as $game)
    @php $rule = $game->scoringRule; @endphp
    <div class="liquid-glass rounded-2xl overflow-hidden" id="game-{{ $game->id }}">
        {{-- Header --}}
        <button type="button"
                onclick="toggleCard({{ $game->id }})"
                class="w-full px-5 py-4 flex items-center gap-4 text-right">
            <div class="flex-1 flex items-center gap-4 min-w-0">
                <div class="flex-shrink-0">
                    @if($rule)
                    <div class="w-2.5 h-2.5 rounded-full" style="background:#00e476;" title="قانون سفارشی"></div>
                    @else
                    <div class="w-2.5 h-2.5 rounded-full" style="background:rgba(185,203,185,0.25);" title="پیش‌فرض"></div>
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm text-white truncate">
                        {{ $game->homeTeam?->name }} <span style="color:rgba(185,203,185,0.4);">vs</span> {{ $game->awayTeam?->name }}
                    </p>
                    <p class="text-[10px]" style="color:rgba(185,203,185,0.5);">
                        {{ $game->stage_label }} · {{ $game->scheduled_at?->format('Y/m/d') }}
                        @if($game->status === 'finished') · <span style="color:#00e476;">تمام شده</span> @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-4 flex-shrink-0">
                @if($rule)
                <div class="text-xs text-center">
                    <span class="font-black font-heading" style="color:#00e476;">{{ $rule->points_exact }}</span>
                    <span class="text-[10px]" style="color:rgba(185,203,185,0.4);"> / {{ $rule->points_diff }} / {{ $rule->points_outcome }} / {{ $rule->points_participation }}</span>
                </div>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="background:rgba(0,228,118,0.12);color:#00e476;">×{{ $rule->multiplier }}</span>
                @else
                <span class="text-xs" style="color:rgba(185,203,185,0.35);">پیش‌فرض</span>
                @endif
                <span class="material-symbols-outlined text-sm chevron-{{ $game->id }}" style="color:rgba(185,203,185,0.4);transition:transform 0.2s;">expand_more</span>
            </div>
        </button>

        {{-- Form (collapsed by default) --}}
        <div id="card-{{ $game->id }}" class="hidden border-t" style="border-color:rgba(255,255,255,0.07);">
            <form method="POST" action="{{ route('admin.scoring-rules.update', $game) }}" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php $d = \App\Models\GameScoringRule::defaults(); @endphp
                    @foreach([
                        ['name'=>'points_exact',         'label'=>'پیش‌بینی دقیق', 'color'=>'#00e476',  'default'=>$d['points_exact']],
                        ['name'=>'points_diff',          'label'=>'اختلاف گل درست','color'=>'#4D9FFF',  'default'=>$d['points_diff']],
                        ['name'=>'points_outcome',       'label'=>'روند درست',      'color'=>'#F59E0B',  'default'=>$d['points_outcome']],
                        ['name'=>'points_participation', 'label'=>'شرکت',           'color'=>'rgba(185,203,185,0.6)', 'default'=>$d['points_participation']],
                    ] as $field)
                    <div>
                        <label class="block text-xs mb-1 font-semibold" style="color:{{ $field['color'] }};">
                            {{ $field['label'] }}
                        </label>
                        <input type="number" name="{{ $field['name'] }}" min="0" max="100" required
                               value="{{ $rule ? $rule->{$field['name']} : $field['default'] }}"
                               class="stitch-input text-center text-lg font-black w-full"
                               style="color:{{ $field['color'] }};">
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs mb-1 font-semibold text-white">ضریب بازی (×)</label>
                        <input type="number" name="multiplier" min="0" max="10" step="0.1" required
                               value="{{ $rule ? $rule->multiplier : '1.00' }}"
                               class="stitch-input text-center text-lg font-black w-full" style="color:#A78BFA;">
                        <p class="text-[10px] mt-1" style="color:rgba(185,203,185,0.4);">۱.۰ = عادی | ۲.۰ = دو برابر | ۰ = بدون امتیاز</p>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                        <input type="checkbox" name="is_active" value="1" id="active-{{ $game->id }}"
                               {{ (!$rule || $rule->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 accent-[#00e476]">
                        <label for="active-{{ $game->id }}" class="text-xs font-bold text-white cursor-pointer">
                            امتیازدهی فعال است
                        </label>
                    </div>
                    <div class="flex items-center gap-3 p-4 rounded-xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                        <input type="checkbox" name="recalculate_now" value="1" id="recalc-{{ $game->id }}"
                               class="w-4 h-4 accent-[#F59E0B]"
                               {{ $game->status === 'finished' ? '' : 'disabled' }}>
                        <label for="recalc-{{ $game->id }}" class="text-xs font-bold cursor-pointer" style="color:{{ $game->status === 'finished' ? '#F59E0B' : 'rgba(185,203,185,0.3)' }};">
                            بازمحاسبه فوری
                        </label>
                    </div>
                </div>

                <input type="text" name="notes" placeholder="دلیل تغییر قانون (اختیاری)" class="stitch-input text-sm w-full"
                       value="{{ $rule?->notes }}">

                <div class="flex gap-3 justify-end">
                    @if($rule)
                    <form method="POST" action="{{ route('admin.scoring-rules.destroy', $game) }}" class="inline" onsubmit="return confirm('قانون حذف شود؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs px-4 py-2.5 rounded-xl font-bold" style="background:rgba(255,107,107,0.1);border:1px solid rgba(255,107,107,0.2);color:#FF6B6B;">بازگشت به پیش‌فرض</button>
                    </form>
                    @endif
                    <button type="submit" class="btn-primary text-sm py-2.5 px-6">ذخیره قانون</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">{{ $games->links() }}</div>

@push('scripts')
<script>
function toggleCard(id) {
    const card = document.getElementById(`card-${id}`);
    const chevron = document.querySelector(`.chevron-${id}`);
    const isHidden = card.classList.contains('hidden');
    card.classList.toggle('hidden', !isHidden);
    chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
}
// اگر قانون سفارشی دارد، باز باشه
@foreach($games as $game)
@if($game->scoringRule) toggleCard({{ $game->id }}); @endif
@endforeach
</script>
@endpush
@endsection
