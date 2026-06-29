@extends('layouts.admin')
@section('title', 'پروفایل ' . $user->name)
@section('page-title', 'پروفایل کاربر')

@section('content')

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ── ستون چپ: اطلاعات کاربر + ابزارها ── --}}
    <div class="space-y-4">

        {{-- کارت پروفایل --}}
        <div class="liquid-glass rounded-2xl p-5 text-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl font-black font-heading mx-auto mb-3"
                 style="background:rgba(0,228,118,0.12);color:#00e476;">
                {{ mb_strtoupper(mb_substr($user->name,0,1,'UTF-8')) }}
            </div>
            <h2 class="font-black text-lg font-heading text-white">{{ $user->name }}</h2>
            <p class="text-xs mt-1" style="color:rgba(185,203,185,0.5);">{{ $user->email }}</p>
            @if($user->department)
            <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full" style="background:rgba(77,159,255,0.12);color:#4D9FFF;">{{ $user->department }}</span>
            @endif

            <div class="flex justify-center gap-6 mt-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.07);">
                <div>
                    <p class="text-2xl font-black font-heading" style="color:#00e476;">{{ $user->total_score + ($user->score_adjustment ?? 0) }}</p>
                    <p class="text-[10px]" style="color:rgba(185,203,185,0.5);">امتیاز</p>
                </div>
                <div>
                    <p class="text-2xl font-black font-heading text-white">{{ $user->rank }}</p>
                    <p class="text-[10px]" style="color:rgba(185,203,185,0.5);">رتبه</p>
                </div>
                <div>
                    <p class="text-2xl font-black font-heading" style="color:#4D9FFF;">{{ $stats['total_predictions'] }}</p>
                    <p class="text-[10px]" style="color:rgba(185,203,185,0.5);">پیش‌بینی</p>
                </div>
            </div>
        </div>

        {{-- ویرایش مشخصات کاربر --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="material-symbols-outlined text-sm" style="color:#00e476;">edit</span>
                <span class="text-xs font-bold text-white">ویرایش مشخصات</span>
            </div>
            <form method="POST" action="{{ route('admin.users.profile', $user) }}" class="p-4 space-y-3">
                @csrf
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">نام</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="stitch-input text-sm w-full">
                </div>
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">نام کاربری</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="stitch-input text-sm w-full" placeholder="اختیاری — فقط انگلیسی و عدد">
                </div>
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">ایمیل</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="stitch-input text-sm w-full">
                </div>
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">واحد / دپارتمان</label>
                    <input type="text" name="department" value="{{ old('department', $user->department) }}" class="stitch-input text-sm w-full" placeholder="اختیاری">
                </div>
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">رمز عبور جدید (اختیاری)</label>
                    <input type="password" name="password" class="stitch-input text-sm w-full" placeholder="خالی = بدون تغییر">
                </div>
                <div>
                    <label class="text-xs mb-1 block" style="color:rgba(185,203,185,0.6);">تکرار رمز عبور</label>
                    <input type="password" name="password_confirmation" class="stitch-input text-sm w-full">
                </div>
                <button type="submit" class="btn-primary text-xs py-2.5 w-full">ذخیره تغییرات</button>
            </form>
        </div>

        {{-- Stats توزیع --}}
        <div class="liquid-glass rounded-2xl p-4 space-y-2">
            @php $tiers = [
                ['label'=>'پیش‌بینی دقیق', 'val'=>$stats['exact'],       'pts'=>'۱۰', 'color'=>'#00e476'],
                ['label'=>'اختلاف گل درست','val'=>$stats['diff'],        'pts'=>'۷',  'color'=>'#4D9FFF'],
                ['label'=>'روند درست',      'val'=>$stats['outcome'],     'pts'=>'۵',  'color'=>'#F59E0B'],
                ['label'=>'شرکت',           'val'=>$stats['participation'],'pts'=>'۲', 'color'=>'rgba(185,203,185,0.4)'],
            ]; $total = max(1,$stats['total_predictions']); @endphp
            @foreach($tiers as $t)
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span style="color:{{ $t['color'] }};">{{ $t['label'] }} ({{ $t['pts'] }}pt)</span>
                    <span class="font-mono text-white">{{ $t['val'] }}</span>
                </div>
                <div class="h-1.5 rounded-full" style="background:rgba(255,255,255,0.06);">
                    <div class="h-1.5 rounded-full transition-all" style="width:{{ $total > 0 ? round($t['val']/$total*100) : 0 }}%;background:{{ $t['color'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- وضعیت + تغییر --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="text-xs font-bold text-white">وضعیت حساب</span>
                @if($user->is_active)
                <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(0,228,118,0.12);color:#00e476;">فعال</span>
                @else
                <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(255,107,107,0.12);color:#FF6B6B;">غیرفعال</span>
                @endif
            </div>
            <div class="p-4">
                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="flex gap-2">
                    @csrf
                    <input type="text" name="reason" placeholder="دلیل (اختیاری)" class="stitch-input text-xs flex-1">
                    <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl transition-all"
                            style="background:{{ $user->is_active ? 'rgba(255,107,107,0.12)' : 'rgba(0,228,118,0.12)' }};color:{{ $user->is_active ? '#FF6B6B' : '#00e476' }};border:1px solid {{ $user->is_active ? 'rgba(255,107,107,0.3)' : 'rgba(0,228,118,0.3)' }};">
                        {{ $user->is_active ? 'غیرفعال' : 'فعال' }} کن
                    </button>
                </form>
            </div>
        </div>

        {{-- حذف کاربر --}}
        <div class="liquid-glass rounded-2xl overflow-hidden" style="border-color:rgba(255,107,107,0.2);">
            <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid rgba(255,107,107,0.15);">
                <span class="material-symbols-outlined text-sm" style="color:#FF6B6B;">delete_forever</span>
                <span class="text-xs font-bold" style="color:#FF6B6B;">حذف کاربر</span>
            </div>
            <div class="p-4 space-y-3">
                <p class="text-xs" style="color:rgba(185,203,185,0.6);">با حذف کاربر، تمام پیش‌بینی‌هایش نیز پاک می‌شود. این عمل برگشت‌پذیر نیست.</p>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('آیا مطمئن هستید؟ کاربر «{{ $user->name }}» و تمام پیش‌بینی‌هایش حذف خواهد شد.')">
                    @csrf
                    @method('DELETE')
                    <input type="text" name="reason" placeholder="دلیل حذف (اختیاری)" class="stitch-input text-xs w-full mb-2">
                    <button type="submit" class="w-full text-xs font-bold py-2.5 rounded-xl transition-all"
                            style="background:rgba(255,107,107,0.1);color:#FF6B6B;border:1px solid rgba(255,107,107,0.3);"
                            onmouseover="this.style.background='rgba(255,107,107,0.2)'" onmouseout="this.style.background='rgba(255,107,107,0.1)'">
                        حذف دائمی کاربر
                    </button>
                </form>
            </div>
        </div>

        {{-- Manual Override امتیاز --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="material-symbols-outlined text-sm" style="color:#F59E0B;">tune</span>
                <span class="text-xs font-bold text-white">تنظیم دستی امتیاز</span>
                @if($user->score_adjustment != 0)
                <span class="text-xs font-mono {{ $user->score_adjustment > 0 ? 'text-green-400' : 'text-red-400' }}">
                    (فعلی: {{ $user->score_adjustment > 0 ? '+' : '' }}{{ $user->score_adjustment }})
                </span>
                @endif
            </div>
            <form method="POST" action="{{ route('admin.users.override', $user) }}" class="p-4 space-y-3">
                @csrf
                <div class="flex gap-2">
                    <input type="number" name="adjustment" placeholder="مثبت یا منفی" required
                           class="stitch-input text-xs flex-1">
                </div>
                <input type="text" name="reason" placeholder="دلیل تغییر (الزامی)" required class="stitch-input text-xs w-full">
                <button type="submit" class="btn-primary text-xs py-2.5 w-full">اعمال تنظیم</button>
            </form>
        </div>

        {{-- یادداشت ادمین --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-4 py-3 flex items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,0.07);">
                <span class="material-symbols-outlined text-sm" style="color:#A78BFA;">note</span>
                <span class="text-xs font-bold text-white">یادداشت ادمین</span>
            </div>
            <form method="POST" action="{{ route('admin.users.note', $user) }}" class="p-4 space-y-3">
                @csrf
                <textarea name="admin_note" rows="3" placeholder="یادداشت درباره این کاربر..."
                          class="stitch-input text-xs w-full resize-none">{{ $user->admin_note }}</textarea>
                <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl w-full" style="background:rgba(167,139,250,0.1);border:1px solid rgba(167,139,250,0.25);color:#A78BFA;">ذخیره یادداشت</button>
            </form>
        </div>
    </div>

    {{-- ── ستون راست: پیش‌بینی‌ها + Audit Log ── --}}
    <div class="xl:col-span-2 space-y-4">

        {{-- پیش‌بینی‌ها --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <span class="material-symbols-outlined text-base" style="color:#4D9FFF;">sports_soccer</span>
                <h3 class="font-black text-sm font-heading text-white">تاریخچه پیش‌بینی‌ها</h3>
                <span class="text-xs px-2 py-0.5 rounded-full font-mono" style="background:rgba(77,159,255,0.12);color:#4D9FFF;">{{ $predictions->total() }}</span>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <th class="px-4 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">بازی</th>
                        <th class="px-4 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.5);">پیش‌بینی</th>
                        <th class="px-4 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.5);">نتیجه واقعی</th>
                        <th class="px-4 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.5);">امتیاز</th>
                        <th class="px-4 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.5);">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($predictions as $p)
                    @php
                        $pts = $p->points_override ?? $p->points_earned;
                        $ptColor = match($pts) { 10=>'#00e476', 7=>'#4D9FFF', 5=>'#F59E0B', 2,0=>'rgba(185,203,185,0.5)', default=>'rgba(185,203,185,0.3)' };
                    @endphp
                    <tr class="border-b prediction-row {{ $p->is_admin_edited ? 'bg-amber-500/5' : '' }}"
                        style="border-color:rgba(255,255,255,0.04);"
                        data-prediction="{{ $p->id }}"
                        onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                        onmouseout="this.style.background='{{ $p->is_admin_edited ? 'rgba(245,158,11,0.05)' : '' }}'">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-white text-xs leading-tight">
                                {{ $p->game?->homeTeam?->name }} <span style="color:rgba(185,203,185,0.4);">vs</span> {{ $p->game?->awayTeam?->name }}
                            </p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(185,203,185,0.4);">
                                {{ $p->game?->scheduled_at?->format('Y/m/d') }}
                                @if($p->is_admin_edited)
                                <span class="mr-1" style="color:#F59E0B;">● ویرایش ادمین</span>
                                @endif
                            </p>
                        </td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-white">{{ $p->home_score }} - {{ $p->away_score }}</td>
                        <td class="px-4 py-3 text-center font-mono" style="color:rgba(185,203,185,0.7);">
                            {{ $p->game?->home_score !== null ? $p->game->home_score . ' - ' . $p->game->away_score : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-black font-heading" style="color:{{ $ptColor }};">
                                {{ $pts !== null ? $pts : '—' }}
                            </span>
                            @if($p->points_override !== null)
                            <span class="text-[9px] block" style="color:#F59E0B;">override</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="openEditModal({{ $p->id }}, {{ $p->home_score }}, {{ $p->away_score }})"
                                        class="p-1 rounded-lg" style="background:rgba(77,159,255,0.1);" title="ویرایش پیش‌بینی">
                                    <span class="material-symbols-outlined" style="font-size:13px;color:#4D9FFF;">edit</span>
                                </button>
                                <button onclick="openPointsModal({{ $p->id }}, {{ $pts ?? 'null' }})"
                                        class="p-1 rounded-lg" style="background:rgba(245,166,35,0.1);" title="Override امتیاز">
                                    <span class="material-symbols-outlined" style="font-size:13px;color:#F59E0B;">tune</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="px-5 py-3">{{ $predictions->links() }}</div>
        </div>

        {{-- Audit Log این کاربر --}}
        @if($auditLogs->isNotEmpty())
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <span class="material-symbols-outlined text-base" style="color:#A78BFA;">history</span>
                <h3 class="font-black text-sm font-heading text-white">تاریخچه تغییرات ادمین</h3>
            </div>
            <div class="divide-y" style="--tw-divide-opacity:0.05;border-color:rgba(255,255,255,0.05);">
                @foreach($auditLogs as $log)
                <div class="px-5 py-3 flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background:{{ $log->action_color }};"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-white">{{ $log->action_label }}</span>
                            <span class="text-[10px] font-mono flex-shrink-0" style="color:rgba(185,203,185,0.4);">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        @if($log->reason)
                        <p class="text-[10px] mt-0.5" style="color:rgba(185,203,185,0.5);">{{ $log->reason }}</p>
                        @endif
                        <p class="text-[10px]" style="color:rgba(185,203,185,0.35);">توسط: {{ $log->admin?->name }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal: Edit Prediction --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.6);">
    <div class="liquid-glass rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="font-black text-base font-heading text-white mb-4">ویرایش پیش‌بینی</h3>
        <form id="edit-form" method="POST" class="space-y-4">
            @csrf
            <div class="flex gap-3 items-center justify-center">
                <div class="text-center">
                    <p class="text-xs mb-1" style="color:rgba(185,203,185,0.6);">گل تیم اول</p>
                    <input type="number" name="home_score" min="0" max="99" required class="stitch-input text-center text-2xl font-black w-20">
                </div>
                <span class="text-2xl font-heading text-white mt-5">-</span>
                <div class="text-center">
                    <p class="text-xs mb-1" style="color:rgba(185,203,185,0.6);">گل تیم دوم</p>
                    <input type="number" name="away_score" min="0" max="99" required class="stitch-input text-center text-2xl font-black w-20">
                </div>
            </div>
            <input type="text" name="admin_note" placeholder="دلیل ویرایش (الزامی)" required class="stitch-input text-sm w-full">
            <div class="flex gap-3">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl text-sm font-bold" style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.7);">انصراف</button>
                <button type="submit" class="btn-primary flex-1 py-3 text-sm">ذخیره</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Override Points --}}
<div id="points-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.6);">
    <div class="liquid-glass rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="font-black text-base font-heading text-white mb-1">Override امتیاز</h3>
        <p class="text-xs mb-4" style="color:rgba(185,203,185,0.5);">امتیاز محاسبه‌شده سیستم را نادیده می‌گیرد</p>
        <form id="points-form" method="POST" class="space-y-4">
            @csrf
            <input type="number" name="points_override" min="0" max="100" placeholder="امتیاز جدید (خالی=حذف override)" class="stitch-input text-center text-2xl font-black w-full">
            <input type="text" name="admin_note" placeholder="دلیل (الزامی)" required class="stitch-input text-sm w-full">
            <div class="flex gap-3">
                <button type="button" onclick="closeModals()" class="flex-1 py-3 rounded-xl text-sm font-bold" style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.7);">انصراف</button>
                <button type="submit" class="flex-1 py-3 rounded-xl text-sm font-bold" style="background:rgba(245,166,35,0.15);border:1px solid rgba(245,166,35,0.3);color:#F59E0B;">اعمال</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id, home, away) {
    document.getElementById('edit-form').action = `/admin/predictions/${id}/edit`;
    document.querySelector('#edit-modal [name=home_score]').value = home;
    document.querySelector('#edit-modal [name=away_score]').value = away;
    document.getElementById('edit-modal').classList.replace('hidden','flex');
}
function openPointsModal(id, pts) {
    document.getElementById('points-form').action = `/admin/predictions/${id}/points-override`;
    const inp = document.querySelector('#points-modal [name=points_override]');
    inp.value = pts !== null ? pts : '';
    document.getElementById('points-modal').classList.replace('hidden','flex');
}
function closeModals() {
    document.getElementById('edit-modal').classList.replace('flex','hidden');
    document.getElementById('points-modal').classList.replace('flex','hidden');
}
</script>
@endpush
@endsection
