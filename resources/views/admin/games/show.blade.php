@extends('layouts.admin')

@section('title', 'ثبت نتیجه')
@section('page-title', $game->homeTeam->name . ' vs ' . $game->awayTeam->name)

@section('content')

<div class="max-w-xl">

    {{-- Game Info --}}
    <div class="rounded-2xl border p-5 mb-5" style="background-color:#0F172A; border-color:#334155;">
        <div class="flex items-center justify-between gap-4">
            <div class="text-center flex-1">
                <p class="font-bold text-lg" style="color:#F8FAFC;">{{ $game->homeTeam->name }}</p>
                <p class="text-xs mt-0.5" style="color:#94A3B8;">{{ $game->homeTeam->code }}</p>
            </div>
            <div class="text-center px-4">
                @if($game->status === 'finished')
                    <p class="text-3xl font-bold" style="color:#22C55E; font-family:'Poppins',sans-serif;">
                        {{ $game->home_score }}–{{ $game->away_score }}
                    </p>
                    <p class="text-xs mt-1" style="color:#94A3B8;">نتیجه نهایی ۹۰ دقیقه</p>
                @else
                    <p class="text-lg font-bold" style="color:#475569;">vs</p>
                @endif
            </div>
            <div class="text-center flex-1">
                <p class="font-bold text-lg" style="color:#F8FAFC;">{{ $game->awayTeam->name }}</p>
                <p class="text-xs mt-0.5" style="color:#94A3B8;">{{ $game->awayTeam->code }}</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t flex items-center gap-4 text-xs" style="border-color:#334155; color:#94A3B8;">
            <span>{{ $game->stage_label }}</span>
            @if($game->scheduled_at)
                <span>{{ $game->scheduled_at->format('j M Y — H:i') }}</span>
            @endif
            @if($game->venue)
                <span>{{ $game->venue }}</span>
            @endif
            @if($game->is_disciplinary)
                <span class="px-2 py-0.5 rounded-md" style="background-color:#450a0a; color:#fca5a5;">انضباطی</span>
            @endif
        </div>
    </div>

    @if($game->status !== 'finished')
        {{-- Submit Result Form --}}
        <div class="rounded-2xl border p-6 mb-5" style="background-color:#0F172A; border-color:#334155;">
            <h2 class="font-semibold text-sm mb-5" style="color:#F8FAFC; font-family:'Poppins',sans-serif;">ثبت نتیجه ۹۰ دقیقه</h2>

            <form method="POST" action="{{ route('admin.games.result', $game) }}" class="space-y-4">
                @csrf

                @if($errors->any())
                    <div class="px-4 py-3 rounded-lg text-sm" style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-xs font-medium mb-1.5 text-center" style="color:#94A3B8;">{{ $game->homeTeam->name }}</label>
                        <input type="number" name="home_score" value="{{ old('home_score', 0) }}" min="0" max="99" required
                               class="w-full px-4 py-3 rounded-xl text-center text-xl font-bold outline-none"
                               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                               onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                    </div>
                    <span class="text-2xl font-bold flex-shrink-0" style="color:#475569;">–</span>
                    <div class="flex-1">
                        <label class="block text-xs font-medium mb-1.5 text-center" style="color:#94A3B8;">{{ $game->awayTeam->name }}</label>
                        <input type="number" name="away_score" value="{{ old('away_score', 0) }}" min="0" max="99" required
                               class="w-full px-4 py-3 rounded-xl text-center text-xl font-bold outline-none"
                               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                               onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                    </div>
                </div>

                @if($game->isKnockout())
                    <div class="p-3 rounded-xl text-xs" style="background-color:#1E293B; color:#94A3B8;">
                        برای مسابقه حذفی، فقط نتیجه ۹۰ دقیقه را ثبت کنید. نتیجه وقت اضافه/پنالتی در فیلد نهایی ذخیره می‌شود ولی برای امتیازدهی مهم نیست.
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <label class="block text-xs font-medium mb-1.5 text-center" style="color:#94A3B8;">نتیجه نهایی (اختیاری)</label>
                            <input type="number" name="home_score_final" value="{{ old('home_score_final') }}" min="0" max="99"
                                   placeholder="–"
                                   class="w-full px-4 py-2.5 rounded-xl text-center text-sm font-bold outline-none"
                                   style="background-color:#1E293B; border:1px solid #334155; color:#94A3B8;"
                                   onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                        </div>
                        <span class="text-xl font-bold flex-shrink-0" style="color:#334155;">–</span>
                        <div class="flex-1">
                            <label class="block text-xs font-medium mb-1.5 text-center" style="color:#94A3B8;">&nbsp;</label>
                            <input type="number" name="away_score_final" value="{{ old('away_score_final') }}" min="0" max="99"
                                   placeholder="–"
                                   class="w-full px-4 py-2.5 rounded-xl text-center text-sm font-bold outline-none"
                                   style="background-color:#1E293B; border:1px solid #334155; color:#94A3B8;"
                                   onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                        </div>
                    </div>
                @endif

                <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-bold cursor-pointer transition-colors"
                        style="background-color:#22C55E; color:#020617;"
                        onmouseover="this.style.backgroundColor='#16A34A';"
                        onmouseout="this.style.backgroundColor='#22C55E';">
                    ثبت نتیجه و محاسبه امتیازات
                </button>
            </form>
        </div>
    @else
        {{-- Scoring Stats --}}
        @php
            $stats = app(\App\Services\PredictionScoringService::class)->getGameStats($game);
        @endphp
        <div class="rounded-2xl border p-5 mb-5" style="background-color:#0F172A; border-color:#334155;">
            <h2 class="font-semibold text-sm mb-4" style="color:#F8FAFC;">آمار امتیازدهی</h2>
            <div class="grid grid-cols-5 gap-2 text-center">
                @foreach([10 => '#22C55E', 7 => '#60a5fa', 5 => '#a78bfa', 2 => '#94A3B8', 0 => '#475569'] as $pts => $color)
                    <div class="rounded-xl p-3" style="background-color:#1E293B;">
                        <p class="text-xl font-bold" style="color:{{ $color }};">{{ $stats['breakdown'][$pts] }}</p>
                        <p class="text-xs mt-1" style="color:#475569;">+{{ $pts }}</p>
                    </div>
                @endforeach
            </div>
            <p class="text-xs mt-3 text-center" style="color:#94A3B8;">
                {{ $stats['scored'] }} از {{ $stats['total'] }} پیش‌بینی ارزیابی شد
            </p>
        </div>
    @endif

    <a href="{{ route('admin.games.index') }}"
       class="inline-flex items-center gap-2 text-sm font-medium"
       style="color:#94A3B8;"
       onmouseover="this.style.color='#F8FAFC';"
       onmouseout="this.style.color='#94A3B8';">
        ← بازگشت به لیست بازی‌ها
    </a>
</div>

@endsection
