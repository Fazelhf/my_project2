@extends('layouts.admin')
@section('title', 'مدیریت پیش‌بینی قهرمانی')
@section('page-title', 'پیش‌بینی قهرمانی')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Settings --}}
    <div class="glass-card rounded-3xl p-6">
        <h2 class="text-base font-black font-heading text-white mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined" style="color:#00e476;">settings</span>
            تنظیمات
        </h2>

        <form action="{{ route('admin.tournament.update') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold mb-2 text-white">زمان قفل‌شدن پیش‌بینی</label>
                <input type="datetime-local" name="prediction_lock_time"
                       value="{{ $settings['prediction_lock_time'] ?? '' }}"
                       class="stitch-input w-full">
                <p class="text-xs mt-1" style="color:rgba(185,203,185,0.4);">بعد از این زمان کاربران نمی‌توانند تغییر دهند.</p>
            </div>

            <div style="border-top:1px solid rgba(255,255,255,0.07);padding-top:16px;">
                <p class="text-sm font-bold text-white mb-3">نتایج رسمی جام جهانی</p>

                @foreach([['label'=>'قهرمان (۱۰۰ امتیاز)','key'=>'actual_champion'],['label'=>'نایب‌قهرمان (۵۰ امتیاز)','key'=>'actual_runner_up'],['label'=>'تیم سوم (۵۰ امتیاز)','key'=>'actual_third_place']] as $slot)
                <div class="mb-3">
                    <label class="block text-xs font-bold mb-1" style="color:rgba(185,203,185,0.7);">{{ $slot['label'] }}</label>
                    <select name="{{ $slot['key'] }}" class="stitch-input w-full">
                        <option value="">انتخاب نشده</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ ($settings[$slot['key']] ?? '') == $team->id ? 'selected' : '' }}>
                                {{ $team->name_fa ?? $team->name }} ({{ $team->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endforeach

                <p class="text-xs" style="color:rgba(185,203,185,0.4);">پس از ذخیره، امتیازات تمام پیش‌بینی‌ها محاسبه می‌شود.</p>
            </div>

            <button type="submit" class="btn-primary w-full py-3">
                <span class="material-symbols-outlined text-base">save</span>
                ذخیره تنظیمات
            </button>
        </form>
    </div>

    {{-- Predictions table --}}
    <div class="glass-card rounded-3xl p-6">
        <h2 class="text-base font-black font-heading text-white mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined" style="color:#FFD700;">emoji_events</span>
            پیش‌بینی‌های کاربران ({{ $predictions->count() }})
        </h2>

        <div class="space-y-2 overflow-y-auto" style="max-height:500px;">
            @foreach($predictions as $pred)
            <div class="rounded-xl px-4 py-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-bold text-white">{{ $pred->user->name }}</span>
                    @if($pred->total_points > 0)
                        <span class="badge badge-green text-xs">+{{ $pred->total_points }}</span>
                    @endif
                </div>
                <div class="grid grid-cols-3 gap-2 text-[10px]">
                    <div>
                        <span style="color:rgba(185,203,185,0.4);">قهرمان:</span><br>
                        <span style="color:#FFD700;">{{ $pred->champion?->name_fa ?? $pred->champion?->name ?? '—' }}</span>
                    </div>
                    <div>
                        <span style="color:rgba(185,203,185,0.4);">نایب:</span><br>
                        <span style="color:#C0C0C0;">{{ $pred->runnerUp?->name_fa ?? $pred->runnerUp?->name ?? '—' }}</span>
                    </div>
                    <div>
                        <span style="color:rgba(185,203,185,0.4);">سوم:</span><br>
                        <span style="color:#CD7F32;">{{ $pred->thirdPlace?->name_fa ?? $pred->thirdPlace?->name ?? '—' }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            @if($predictions->isEmpty())
            <p class="text-sm text-center py-8" style="color:rgba(185,203,185,0.3);">هنوز پیش‌بینی ثبت نشده است.</p>
            @endif
        </div>
    </div>

</div>

@endsection
