@extends('layouts.admin')
@section('title', 'وارد کردن پیش‌بینی‌ها')
@section('page-title', 'import پیش‌بینی‌ها')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl text-sm font-bold" style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.25);color:#00e476;">
    {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background:rgba(255,90,90,0.1);border:1px solid rgba(255,90,90,0.25);color:#FF8A8A;">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

{{-- ── Step 1: انتخاب کاربر + JSON import ────────────────────── --}}
<div class="liquid-glass rounded-2xl overflow-hidden mb-4">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-base" style="color:#A78BFA;">person</span>
            <h2 class="font-black text-sm font-heading text-white">انتخاب کاربر</h2>
        </div>
        @if(request('user_id'))
        <button type="button" onclick="document.getElementById('jsonPanel').classList.toggle('hidden')"
                class="text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5"
                style="background:rgba(77,159,255,0.12);color:#4D9FFF;border:1px solid rgba(77,159,255,0.25);">
            <span class="material-symbols-outlined text-sm">upload_file</span>
            ایمپورت JSON
        </button>
        @endif
    </div>

    {{-- JSON import panel --}}
    @if(request('user_id'))
    <div id="jsonPanel" class="hidden px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.06);background:rgba(77,159,255,0.04);">
        <p class="text-xs mb-3" style="color:rgba(185,203,185,0.6);">
            فرمت JSON: آرایه‌ای از <code style="color:#4D9FFF;">[{"game_id":1,"home_score":2,"away_score":1}, ...]</code>
        </p>
        <form method="POST" action="{{ route('admin.import.predictions.json') }}" enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
            @csrf
            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            <div>
                <label class="text-xs font-bold mb-1.5 block" style="color:rgba(185,203,185,0.7);">فایل JSON</label>
                <input type="file" name="json_file" accept=".json,application/json" required class="stitch-input text-xs px-3 py-2">
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5"
                    style="background:rgba(77,159,255,0.15);color:#4D9FFF;border:1px solid rgba(77,159,255,0.3);">
                <span class="material-symbols-outlined text-sm">upload</span>
                آپلود و ذخیره
            </button>
        </form>
    </div>
    @endif

    <div class="p-5">
        <form method="GET" action="{{ route('admin.import.predictions') }}" class="flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-48">
                <label class="text-xs font-bold mb-1.5 block" style="color:rgba(185,203,185,0.7);">کاربر</label>
                <select name="user_id" id="userSelect" class="stitch-input text-sm w-full" onchange="this.form.submit()">
                    <option value="">-- کاربر را انتخاب کنید --</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} — {{ $u->email }}
                    </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

{{-- ── Step 2: جدول بازی‌ها (فقط وقتی کاربر انتخاب شده) ──────── --}}
@if(request('user_id'))
@php
    $selectedUser = $users->firstWhere('id', request('user_id'));
@endphp

<div class="liquid-glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-base" style="color:#00e476;">sports_score</span>
            <div>
                <h2 class="font-black text-sm font-heading text-white">پیش‌بینی‌های {{ $selectedUser?->name }}</h2>
                <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">برای هر بازی نتیجه پیش‌بینی شده را وارد کنید — خانه‌های خالی ذخیره نمی‌شوند</p>
            </div>
        </div>
        <span class="text-xs font-mono px-2 py-1 rounded-lg" style="background:rgba(0,228,118,0.1);color:#00e476;">
            {{ $games->count() }} بازی
        </span>
    </div>

    <form method="POST" action="{{ route('admin.import.predictions.store') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ request('user_id') }}">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.07);background:rgba(255,255,255,0.02);">
                        <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.5);">#</th>
                        <th class="px-4 py-3 text-right text-xs font-bold" style="color:rgba(185,203,185,0.5);">بازی</th>
                        <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">تاریخ</th>
                        <th class="px-4 py-3 text-center text-xs font-bold" style="color:rgba(185,203,185,0.5);">نتیجه واقعی</th>
                        <th class="px-4 py-3 text-center text-xs font-bold w-24" style="color:#4D9FFF;">گل میزبان</th>
                        <th class="px-4 py-3 text-center text-xs font-bold w-24" style="color:#4D9FFF;">گل مهمان</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $i => $game)
                    @php
                        $pred = $existing->get($game->id);
                    @endphp
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.04);"
                        onmouseover="this.style.background='rgba(0,228,118,0.02)'"
                        onmouseout="this.style.background=''">
                        <td class="px-4 py-3 text-xs font-mono" style="color:rgba(185,203,185,0.35);">{{ $i + 1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-white text-xs">{{ $game->homeTeam->name }}</span>
                                <span style="color:rgba(185,203,185,0.4);" class="text-xs">vs</span>
                                <span class="font-bold text-white text-xs">{{ $game->awayTeam->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-xs font-mono" style="color:rgba(185,203,185,0.5);">
                            {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($game->status === 'finished')
                                <span class="font-black font-mono text-sm" style="color:#00e476;">
                                    {{ $game->home_score }} – {{ $game->away_score }}
                                </span>
                            @else
                                <span class="text-xs" style="color:rgba(185,203,185,0.3);">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <input type="number"
                                   name="predictions[{{ $game->id }}][home_score]"
                                   value="{{ old("predictions.{$game->id}.home_score", $pred?->home_score) }}"
                                   min="0" max="99" placeholder="—"
                                   class="stitch-input text-center text-sm w-16 mx-auto block">
                        </td>
                        <td class="px-4 py-2 text-center">
                            <input type="number"
                                   name="predictions[{{ $game->id }}][away_score]"
                                   value="{{ old("predictions.{$game->id}.away_score", $pred?->away_score) }}"
                                   min="0" max="99" placeholder="—"
                                   class="stitch-input text-center text-sm w-16 mx-auto block">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 flex items-center justify-between" style="border-top:1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('admin.import-export') }}" class="text-xs px-4 py-2 rounded-xl"
               style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.6);">انصراف</a>
            <button type="submit" class="btn-primary text-sm px-8 py-2.5 flex items-center gap-2">
                <span class="material-symbols-outlined text-base">save</span>
                ذخیره همه پیش‌بینی‌ها
            </button>
        </div>
    </form>
</div>
@endif

@endsection
