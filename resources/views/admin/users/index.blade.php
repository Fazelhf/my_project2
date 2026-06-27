@extends('layouts.admin')
@section('title', 'مدیریت کاربران')
@section('page-title', 'مدیریت کاربران')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-3 mb-5">
    @php $sc = [
        ['val'=>$stats['total'],    'label'=>'کل کاربران',       'color'=>'#00e476', 'icon'=>'group'],
        ['val'=>$stats['active'],   'label'=>'فعال',              'color'=>'#4D9FFF', 'icon'=>'check_circle'],
        ['val'=>$stats['inactive'], 'label'=>'غیرفعال',           'color'=>'#FF6B6B', 'icon'=>'block'],
    ]; @endphp
    @foreach($sc as $s)
    <div class="liquid-glass rounded-2xl p-4 flex items-center gap-3" style="border-right:3px solid {{ $s['color'] }}40;">
        <span class="material-symbols-outlined text-2xl" style="color:{{ $s['color'] }};">{{ $s['icon'] }}</span>
        <div>
            <p class="text-2xl font-black font-heading" style="color:{{ $s['color'] }};">{{ $s['val'] }}</p>
            <p class="text-xs" style="color:rgba(185,203,185,0.6);">{{ $s['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters + Bulk Form --}}
<form id="bulk-form" method="POST" action="{{ route('admin.users.bulk-action') }}">
@csrf
<div class="liquid-glass rounded-2xl overflow-hidden mb-5">
    <div class="px-5 py-4 flex flex-wrap items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">

        {{-- Search --}}
        <div class="relative flex-1 min-w-52">
            <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 pointer-events-none" style="right:10px;font-size:16px;color:rgba(185,203,185,0.4);">search</span>
            <form method="GET" id="filter-form" class="contents">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="جستجو نام، ایمیل، واحد..."
                       class="stitch-input text-sm w-full" style="padding-right:34px;"
                       onchange="document.getElementById('filter-form').submit()">
            </form>
        </div>

        {{-- Status Filter --}}
        <select name="status" class="stitch-input text-sm" style="width:140px;" onchange="this.form.submit()" form="filter-form">
            <option value="">همه وضعیت‌ها</option>
            <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>فعال</option>
            <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>غیرفعال</option>
        </select>

        {{-- Department Filter --}}
        @if($departments->isNotEmpty())
        <select name="department" class="stitch-input text-sm" style="width:160px;" onchange="this.form.submit()" form="filter-form">
            <option value="">همه واحدها</option>
            @foreach($departments as $d)
            <option value="{{ $d }}" {{ request('department')===$d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        @endif

        <a href="{{ route('admin.users.index') }}" class="text-xs px-3 py-2 rounded-xl" style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.6);">پاک‌کردن</a>
    </div>

    {{-- Bulk Action Bar (shown when items selected) --}}
    <div id="bulk-bar" class="px-5 py-3 hidden items-center gap-3 flex-wrap" style="background:rgba(0,228,118,0.04);border-bottom:1px solid rgba(0,228,118,0.15);">
        <span class="text-xs font-bold" style="color:#00e476;"><span id="selected-count">0</span> کاربر انتخاب شده</span>
        <div class="flex-1"></div>

        <select name="action" id="bulk-action-select" class="stitch-input text-xs" style="width:180px;">
            <option value="">-- عملیات گروهی --</option>
            <option value="activate">فعال‌سازی</option>
            <option value="deactivate">غیرفعال‌سازی</option>
            <option value="score_adjust">تنظیم امتیاز</option>
        </select>

        <div id="bulk-amount-wrap" class="hidden">
            <input type="number" name="amount" placeholder="مقدار (مثبت/منفی)" class="stitch-input text-xs" style="width:160px;">
        </div>

        <input type="text" name="reason" placeholder="دلیل تغییر (الزامی)" class="stitch-input text-xs" style="width:200px;">

        <button type="submit" class="btn-primary text-xs py-2 px-4">اعمال</button>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                <th class="px-5 py-3 text-right w-8">
                    <input type="checkbox" id="select-all" class="accent-[#00e476]" onclick="toggleAll(this)">
                </th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.6);">#</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.6);">کاربر</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.6);">واحد</th>
                <th class="px-5 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.6);">پیش‌بینی</th>
                <th class="px-5 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.6);">امتیاز</th>
                <th class="px-5 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.6);">وضعیت</th>
                <th class="px-5 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.6);">عملیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $u)
            <tr class="border-b transition-all user-row {{ !$u->is_active ? 'opacity-50' : '' }}"
                style="border-color:rgba(255,255,255,0.04);"
                onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                onmouseout="this.style.background=''"
            >
                <td class="px-5 py-4">
                    <input type="checkbox" name="user_ids[]" value="{{ $u->id }}" class="accent-[#00e476] row-check" onchange="updateBulkBar()">
                </td>
                <td class="px-5 py-4 text-xs font-mono" style="color:rgba(185,203,185,0.4);">{{ $users->firstItem() + $i }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black font-heading flex-shrink-0"
                             style="background:rgba(0,228,118,0.12);color:#00e476;">
                            {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                        </div>
                        <div>
                            <a href="{{ route('admin.users.show', $u) }}" class="text-sm font-semibold text-white hover:underline">{{ $u->name }}</a>
                            <p class="text-xs" style="color:rgba(185,203,185,0.5);">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-xs" style="color:rgba(185,203,185,0.7);">{{ $u->department ?? '—' }}</td>
                <td class="px-5 py-4 text-center text-xs font-mono" style="color:rgba(185,203,185,0.7);">
                    {{ $u->predictions_count }}
                    @if($u->exact_count)
                    <span class="text-[10px] font-bold" style="color:#00e476;">({{ $u->exact_count }} دقیق)</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="text-base font-black font-heading" style="color:#00e476;">{{ $u->total_score + ($u->score_adjustment ?? 0) }}</span>
                    @if($u->score_adjustment != 0)
                    <span class="text-[10px] font-mono {{ $u->score_adjustment > 0 ? 'text-green-400' : 'text-red-400' }}">
                        ({{ $u->score_adjustment > 0 ? '+' : '' }}{{ $u->score_adjustment }})
                    </span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    @if($u->is_active)
                    <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(0,228,118,0.12);color:#00e476;">فعال</span>
                    @else
                    <span class="text-xs font-bold px-2 py-1 rounded-full" style="background:rgba(255,107,107,0.12);color:#FF6B6B;">غیرفعال</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.users.show', $u) }}"
                           class="p-1.5 rounded-lg transition-all"
                           style="background:rgba(77,159,255,0.1);"
                           title="مشاهده پروفایل"
                           onmouseover="this.style.background='rgba(77,159,255,0.2)'"
                           onmouseout="this.style.background='rgba(77,159,255,0.1)'">
                            <span class="material-symbols-outlined" style="font-size:14px;color:#4D9FFF;">visibility</span>
                        </a>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $u) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="p-1.5 rounded-lg transition-all"
                                    style="background:{{ $u->is_active ? 'rgba(255,107,107,0.1)' : 'rgba(0,228,118,0.1)' }};"
                                    title="{{ $u->is_active ? 'غیرفعال‌سازی' : 'فعال‌سازی' }}"
                                    onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                <span class="material-symbols-outlined" style="font-size:14px;color:{{ $u->is_active ? '#FF6B6B' : '#00e476' }};">
                                    {{ $u->is_active ? 'block' : 'check_circle' }}
                                </span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center text-sm" style="color:rgba(185,203,185,0.4);">هیچ کاربری یافت نشد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
</form>

{{ $users->links() }}

@push('scripts')
<script>
function toggleAll(cb) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = cb.checked);
    updateBulkBar();
}
function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked').length;
    document.getElementById('selected-count').textContent = checked;
    document.getElementById('bulk-bar').classList.toggle('hidden', checked === 0);
    document.getElementById('bulk-bar').classList.toggle('flex', checked > 0);
}
document.getElementById('bulk-action-select')?.addEventListener('change', function() {
    document.getElementById('bulk-amount-wrap').classList.toggle('hidden', this.value !== 'score_adjust');
});
</script>
@endpush
@endsection
