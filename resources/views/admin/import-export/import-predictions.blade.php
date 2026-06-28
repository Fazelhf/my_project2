@extends('layouts.admin')
@section('title', 'وارد کردن پیش‌بینی‌های سیستم قدیم')
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

<div class="liquid-glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <span class="material-symbols-outlined text-base" style="color:#A78BFA;">history</span>
        <div>
            <h2 class="font-black text-sm font-heading text-white">وارد کردن پیش‌بینی‌های سیستم قدیم</h2>
            <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">برای هر بازی، پیش‌بینی‌های کاربران را از سیستم قبلی وارد کنید</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.import.predictions.store') }}" id="importForm">
        @csrf

        {{-- انتخاب بازی --}}
        <div class="p-5 space-y-4" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <div>
                <label class="text-xs font-bold mb-2 block" style="color:rgba(185,203,185,0.7);">انتخاب بازی</label>
                <select name="game_id" id="gameSelect" required class="stitch-input text-sm w-full" onchange="loadGame(this.value)">
                    <option value="">-- بازی را انتخاب کنید --</option>
                    @foreach($games as $game)
                    <option value="{{ $game->id }}" data-home="{{ $game->homeTeam->name }}" data-away="{{ $game->awayTeam->name }}"
                            {{ old('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->homeTeam->name }} vs {{ $game->awayTeam->name }}
                        — {{ $game->scheduled_at?->timezone('Asia/Tehran')->format('j M Y H:i') }}
                        @if($game->status === 'finished') [{{ $game->home_score }}–{{ $game->away_score }}] @endif
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- جدول پیش‌بینی‌ها --}}
        <div class="p-5">
            <div class="flex items-center justify-between mb-3">
                <label class="text-xs font-bold" style="color:rgba(185,203,185,0.7);">پیش‌بینی‌های کاربران</label>
                <button type="button" onclick="addRow()"
                        class="text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1"
                        style="background:rgba(0,228,118,0.1);border:1px solid rgba(0,228,118,0.25);color:#00e476;">
                    <span class="material-symbols-outlined text-sm">add</span>
                    افزودن ردیف
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="predTable">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.07);">
                            <th class="px-3 py-2 text-right text-xs font-bold" style="color:rgba(185,203,185,0.5);">کاربر</th>
                            <th class="px-3 py-2 text-center text-xs font-bold w-20" style="color:rgba(185,203,185,0.5);">گل میزبان</th>
                            <th class="px-3 py-2 text-center text-xs font-bold w-20" style="color:rgba(185,203,185,0.5);">گل مهمان</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="predRows">
                        {{-- ردیف‌های old() --}}
                        @if(old('predictions'))
                            @foreach(old('predictions') as $i => $row)
                            <tr class="pred-row" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                                <td class="px-3 py-2">
                                    <select name="predictions[{{ $i }}][user_id]" required class="stitch-input text-xs w-full">
                                        <option value="">انتخاب کاربر</option>
                                        @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ $row['user_id'] == $u->id ? 'selected' : '' }}>{{ $u->name }} — {{ $u->email }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" name="predictions[{{ $i }}][home_score]" value="{{ $row['home_score'] }}"
                                           min="0" max="99" required class="stitch-input text-xs text-center w-full">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" name="predictions[{{ $i }}][away_score]" value="{{ $row['away_score'] }}"
                                           min="0" max="99" required class="stitch-input text-xs text-center w-full">
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <button type="button" onclick="removeRow(this)" class="text-xs" style="color:#FF8A8A;">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- هیچ ردیفی نداره --}}
            <div id="emptyMsg" class="text-center py-8" style="color:rgba(185,203,185,0.4);">
                <span class="material-symbols-outlined text-3xl">inbox</span>
                <p class="text-xs mt-1">روی «افزودن ردیف» کلیک کنید</p>
            </div>

            <div class="mt-5 flex items-center gap-3 justify-end">
                <a href="{{ route('admin.import-export') }}" class="text-xs px-4 py-2 rounded-xl"
                   style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.6);">انصراف</a>
                <button type="submit" id="submitBtn"
                        class="btn-primary text-sm px-6 py-2.5" disabled>
                    ذخیره پیش‌بینی‌ها
                </button>
            </div>
        </div>
    </form>
</div>

{{-- قالب ردیف --}}
<template id="rowTpl">
    <tr class="pred-row" style="border-bottom:1px solid rgba(255,255,255,0.04);">
        <td class="px-3 py-2">
            <select name="predictions[__IDX__][user_id]" required class="stitch-input text-xs w-full">
                <option value="">انتخاب کاربر</option>
                @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="predictions[__IDX__][home_score]" placeholder="0"
                   min="0" max="99" required class="stitch-input text-xs text-center w-full">
        </td>
        <td class="px-3 py-2">
            <input type="number" name="predictions[__IDX__][away_score]" placeholder="0"
                   min="0" max="99" required class="stitch-input text-xs text-center w-full">
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button" onclick="removeRow(this)" class="text-xs" style="color:#FF8A8A;">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        </td>
    </tr>
</template>

<script>
let rowIdx = {{ old('predictions') ? count(old('predictions')) : 0 }};

function updateUI() {
    const rows = document.querySelectorAll('.pred-row');
    document.getElementById('emptyMsg').style.display = rows.length ? 'none' : 'block';
    document.getElementById('submitBtn').disabled = rows.length === 0 || !document.getElementById('gameSelect').value;
}

function addRow() {
    const tpl  = document.getElementById('rowTpl').innerHTML.replaceAll('__IDX__', rowIdx++);
    const tbody = document.getElementById('predRows');
    tbody.insertAdjacentHTML('beforeend', tpl);
    updateUI();
}

function removeRow(btn) {
    btn.closest('tr').remove();
    updateUI();
}

function loadGame(val) {
    updateUI();
}

document.getElementById('gameSelect').addEventListener('change', () => updateUI());
updateUI();
</script>

@endsection
