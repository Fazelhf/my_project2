@extends('layouts.app')

@section('title', 'پیش‌بینی بازی‌ها')
@section('page-title', 'پیش‌بینی بازی‌ها')

@section('content')

@php
    $stageLabels = [
        'group'          => 'مرحله گروهی',
        'round_of_16'    => 'جام شانزدهم',
        'quarter_final'  => 'ربع‌نهایی',
        'semi_final'     => 'نیمه‌نهایی',
        'third_place'    => 'رده‌بندی سوم',
        'final'          => 'فینال',
    ];
@endphp

@if($games->isEmpty())
    <div class="rounded-2xl border p-12 text-center" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-sm" style="color:#475569;">هیچ بازی‌ای ثبت نشده است.</p>
    </div>
@endif

@foreach($stageLabels as $stage => $label)
    @if($games->has($stage))
        <div class="mb-8">
            <h2 class="text-base font-bold mb-4 flex items-center gap-2"
                style="color:#F8FAFC; font-family:'Poppins',sans-serif;">
                <span class="w-2 h-2 rounded-full inline-block" style="background-color:#22C55E;"></span>
                {{ $label }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($games[$stage] as $game)
                    @php
                        $pred = $game->predictions->first();
                        $locked = $game->isPredictionLocked();
                        $finished = $game->status === 'finished';
                    @endphp

                    <div class="rounded-2xl border overflow-hidden transition-colors"
                         style="background-color:#0F172A; border-color:{{ $finished ? '#334155' : ($pred ? '#22C55E40' : '#334155') }};">

                        {{-- Card Header --}}
                        <div class="px-4 py-3 border-b flex items-center justify-between"
                             style="border-color:#334155; background-color:#0a1628;">
                            <span class="text-xs font-medium" style="color:#94A3B8;">
                                {{ $game->scheduled_at?->format('j M Y — H:i') }}
                            </span>
                            @if($finished)
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium"
                                      style="background-color:#1E293B; color:#94A3B8;">پایان یافته</span>
                            @elseif($locked)
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium"
                                      style="background-color:#450a0a; color:#fca5a5;">قفل شده</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-md font-medium"
                                      style="background-color:#14532d; color:#86efac;">باز</span>
                            @endif
                        </div>

                        {{-- Teams --}}
                        <div class="px-4 py-4">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex-1 text-center">
                                    <p class="font-bold text-sm leading-tight" style="color:#F8FAFC;">{{ $game->homeTeam->name }}</p>
                                    <p class="text-xs mt-0.5" style="color:#475569;">{{ $game->homeTeam->code }}</p>
                                </div>
                                <div class="flex flex-col items-center gap-1 px-3">
                                    @if($finished)
                                        <span class="font-bold text-xl px-3 py-1 rounded-lg"
                                              style="color:#F8FAFC; background-color:#1E293B; font-family:'Poppins',sans-serif;">
                                            {{ $game->home_score }}–{{ $game->away_score }}
                                        </span>
                                    @else
                                        <span class="text-sm font-medium" style="color:#475569;">vs</span>
                                    @endif
                                </div>
                                <div class="flex-1 text-center">
                                    <p class="font-bold text-sm leading-tight" style="color:#F8FAFC;">{{ $game->awayTeam->name }}</p>
                                    <p class="text-xs mt-0.5" style="color:#475569;">{{ $game->awayTeam->code }}</p>
                                </div>
                            </div>

                            {{-- Prediction section --}}
                            <div class="mt-4">
                                @if($pred)
                                    {{-- Has prediction --}}
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex-1 flex items-center gap-2 px-3 py-2 rounded-xl"
                                             style="background-color:#1E293B;">
                                            <span class="text-xs" style="color:#94A3B8;">پیش‌بینی:</span>
                                            <span class="font-bold text-sm" style="color:#F8FAFC;">
                                                {{ $pred->home_score }}–{{ $pred->away_score }}
                                            </span>
                                            @if($pred->points_earned !== null)
                                                <span class="mr-auto text-xs font-bold px-2 py-0.5 rounded-md"
                                                      style="{{ $pred->points_earned >= 7 ? 'background-color:#14532d; color:#86efac;'
                                                               : ($pred->points_earned >= 5 ? 'background-color:#1e3a5f; color:#93c5fd;'
                                                               : 'background-color:#1E293B; color:#64748b;') }}">
                                                    +{{ $pred->points_earned }}
                                                </span>
                                            @endif
                                        </div>
                                        @if(!$locked && !$finished)
                                            <form method="POST" action="{{ route('games.predict.update', $game) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" name="home_score" value="{{ $pred->home_score }}" min="0" max="99"
                                                       class="w-12 px-2 py-1.5 rounded-lg text-center text-sm outline-none"
                                                       style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                                                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                                                <span style="color:#475569;">–</span>
                                                <input type="number" name="away_score" value="{{ $pred->away_score }}" min="0" max="99"
                                                       class="w-12 px-2 py-1.5 rounded-lg text-center text-sm outline-none"
                                                       style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                                                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                                                        style="background-color:#22C55E; color:#020617;"
                                                        onmouseover="this.style.backgroundColor='#16A34A';"
                                                        onmouseout="this.style.backgroundColor='#22C55E';">
                                                    ویرایش
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @elseif(!$locked && !$finished)
                                    {{-- No prediction yet, can submit --}}
                                    <form method="POST" action="{{ route('games.predict', $game) }}" class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="home_score" value="0" min="0" max="99"
                                               class="flex-1 px-3 py-2 rounded-xl text-center text-sm font-bold outline-none"
                                               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                                               onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                                        <span class="font-bold text-sm" style="color:#475569;">–</span>
                                        <input type="number" name="away_score" value="0" min="0" max="99"
                                               class="flex-1 px-3 py-2 rounded-xl text-center text-sm font-bold outline-none"
                                               style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                                               onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                                        <button type="submit"
                                                class="px-4 py-2 rounded-xl text-sm font-bold cursor-pointer transition-colors"
                                                style="background-color:#22C55E; color:#020617;"
                                                onmouseover="this.style.backgroundColor='#16A34A';"
                                                onmouseout="this.style.backgroundColor='#22C55E';">
                                            ثبت
                                        </button>
                                    </form>
                                @else
                                    <p class="text-xs text-center py-2" style="color:#475569;">
                                        {{ $locked ? 'زمان پیش‌بینی پایان یافته' : 'بدون پیش‌بینی' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach

@endsection
