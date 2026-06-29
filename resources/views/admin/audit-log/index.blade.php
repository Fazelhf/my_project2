@extends('layouts.admin')
@section('title', 'Audit Log')
@section('page-title', 'تاریخچه تغییرات ادمین')

@section('content')

{{-- Filters --}}
<div class="liquid-glass rounded-2xl px-5 py-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">

        <div>
            <p class="text-xs mb-1 font-semibold" style="color:rgba(185,203,185,0.6);">نوع اکشن</p>
            <select name="action" class="stitch-input text-xs" style="width:200px;" onchange="this.form.submit()">
                <option value="">همه اکشن‌ها</option>
                @foreach($actions as $a)
                @php $tmp = new \App\Models\AdminAuditLog(['action'=>$a]); @endphp
                <option value="{{ $a }}" {{ request('action')===$a ? 'selected' : '' }}>{{ $tmp->action_label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <p class="text-xs mb-1 font-semibold" style="color:rgba(185,203,185,0.6);">ادمین</p>
            <select name="admin_id" class="stitch-input text-xs" style="width:160px;" onchange="this.form.submit()">
                <option value="">همه ادمین‌ها</option>
                @foreach($admins as $a)
                <option value="{{ $a->id }}" {{ request('admin_id')==$a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <p class="text-xs mb-1 font-semibold" style="color:rgba(185,203,185,0.6);">نوع هدف</p>
            <select name="target_type" class="stitch-input text-xs" style="width:160px;" onchange="this.form.submit()">
                <option value="">همه</option>
                <option value="User" {{ request('target_type')==='User' ? 'selected' : '' }}>کاربر</option>
                <option value="Prediction" {{ request('target_type')==='Prediction' ? 'selected' : '' }}>پیش‌بینی</option>
                <option value="GameScoringRule" {{ request('target_type')==='GameScoringRule' ? 'selected' : '' }}>قانون امتیازدهی</option>
            </select>
        </div>

        <div>
            <p class="text-xs mb-1 font-semibold" style="color:rgba(185,203,185,0.6);">از تاریخ</p>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="stitch-input text-xs" style="width:140px;" onchange="this.form.submit()">
        </div>

        <div>
            <p class="text-xs mb-1 font-semibold" style="color:rgba(185,203,185,0.6);">تا تاریخ</p>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="stitch-input text-xs" style="width:140px;" onchange="this.form.submit()">
        </div>

        <a href="{{ route('admin.audit-log') }}" class="text-xs px-3 py-2.5 rounded-xl" style="background:rgba(255,255,255,0.05);color:rgba(185,203,185,0.6);">پاک‌کردن</a>
    </form>
</div>

{{-- Log Table --}}
<div class="liquid-glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-base" style="color:#A78BFA;">history</span>
            <h2 class="font-black text-sm font-heading text-white">رویدادها</h2>
        </div>
        <span class="text-xs font-mono px-2 py-0.5 rounded-full" style="background:rgba(167,139,250,0.1);color:#A78BFA;">{{ $logs->total() }} رویداد</span>
    </div>

    <div class="overflow-x-auto">
    <table class="w-full text-xs">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">زمان</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">ادمین</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">اکشن</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">هدف</th>
                <th class="px-5 py-3 text-right font-semibold" style="color:rgba(185,203,185,0.5);">دلیل</th>
                <th class="px-5 py-3 text-center font-semibold" style="color:rgba(185,203,185,0.5);">جزئیات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="border-b"
                style="border-color:rgba(255,255,255,0.04);"
                onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                onmouseout="this.style.background=''">

                <td class="px-5 py-3 whitespace-nowrap">
                    <p class="font-mono text-white">{{ $log->created_at->format('m/d H:i') }}</p>
                    <p style="color:rgba(185,203,185,0.4);">{{ $log->created_at->diffForHumans() }}</p>
                </td>

                <td class="px-5 py-3">
                    <span class="font-semibold text-white">{{ $log->admin?->name ?? '—' }}</span>
                    @if($log->ip_address)
                    <p class="font-mono" style="color:rgba(185,203,185,0.35);">{{ $log->ip_address }}</p>
                    @endif
                </td>

                <td class="px-5 py-3">
                    <span class="inline-flex items-center gap-1.5 font-bold px-2.5 py-1 rounded-full whitespace-nowrap"
                          style="background:{{ $log->action_color }}15;color:{{ $log->action_color }};border:1px solid {{ $log->action_color }}30;">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:{{ $log->action_color }};"></span>
                        {{ $log->action_label }}
                    </span>
                </td>

                <td class="px-5 py-3">
                    <span class="font-mono" style="color:rgba(185,203,185,0.6);">{{ $log->target_type }}</span>
                    <span class="font-mono font-bold text-white">#{{ $log->target_id }}</span>
                    @if($log->target_type === 'User')
                    @php $targetUser = \App\Models\User::find($log->target_id); @endphp
                    @if($targetUser)
                    <p style="color:rgba(185,203,185,0.4);">{{ $targetUser->name }}</p>
                    @endif
                    @endif
                </td>

                <td class="px-5 py-3 max-w-48">
                    <p class="truncate" style="color:rgba(185,203,185,0.7);">{{ $log->reason ?? '—' }}</p>
                </td>

                <td class="px-5 py-3 text-center">
                    @if($log->before || $log->after)
                    <button onclick="showDiff({{ $loop->index }})"
                            class="p-1.5 rounded-lg transition-all"
                            style="background:rgba(167,139,250,0.1);"
                            onmouseover="this.style.background='rgba(167,139,250,0.2)'"
                            onmouseout="this.style.background='rgba(167,139,250,0.1)'">
                        <span class="material-symbols-outlined" style="font-size:14px;color:#A78BFA;">diff</span>
                    </button>
                    @else
                    <span style="color:rgba(185,203,185,0.2);">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-14 text-center" style="color:rgba(185,203,185,0.4);">هیچ رویدادی یافت نشد</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="px-5 py-4">{{ $logs->links() }}</div>
</div>

{{-- Diff Modal --}}
<div id="diff-modal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.7);">
    <div class="liquid-glass rounded-2xl p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-black text-base font-heading text-white">جزئیات تغییر</h3>
            <button onclick="document.getElementById('diff-modal').classList.replace('flex','hidden')"
                    class="p-1 rounded-lg" style="background:rgba(255,255,255,0.06);">
                <span class="material-symbols-outlined text-base" style="color:rgba(185,203,185,0.6);">close</span>
            </button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-bold mb-2" style="color:#FF6B6B;">قبل از تغییر</p>
                <pre id="diff-before" class="text-[11px] font-mono p-3 rounded-xl overflow-x-auto leading-relaxed"
                     style="background:rgba(255,107,107,0.06);border:1px solid rgba(255,107,107,0.15);color:rgba(185,203,185,0.8);"></pre>
            </div>
            <div>
                <p class="text-xs font-bold mb-2" style="color:#00e476;">بعد از تغییر</p>
                <pre id="diff-after" class="text-[11px] font-mono p-3 rounded-xl overflow-x-auto leading-relaxed"
                     style="background:rgba(0,228,118,0.05);border:1px solid rgba(0,228,118,0.15);color:rgba(185,203,185,0.8);"></pre>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const diffs = @json($logs->map(fn($l) => ['before' => $l->before, 'after' => $l->after]));

function showDiff(idx) {
    const d = diffs[idx];
    document.getElementById('diff-before').textContent = d.before ? JSON.stringify(d.before, null, 2) : '—';
    document.getElementById('diff-after').textContent  = d.after  ? JSON.stringify(d.after,  null, 2) : '—';
    document.getElementById('diff-modal').classList.replace('hidden','flex');
}
</script>
@endpush
@endsection
