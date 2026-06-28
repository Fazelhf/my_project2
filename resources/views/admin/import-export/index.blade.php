@extends('layouts.admin')
@section('title', 'ایمپورت / اکسپورت')
@section('page-title', 'ایمپورت / اکسپورت')

@section('content')

{{-- ── Stats ── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @php $sc = [
        ['val'=>$stats['games'],       'label'=>'بازی‌ها',       'color'=>'#00e476', 'icon'=>'sports_soccer'],
        ['val'=>$stats['teams'],       'label'=>'تیم‌ها',        'color'=>'#4D9FFF', 'icon'=>'flag'],
        ['val'=>$stats['users'],       'label'=>'کاربران',       'color'=>'#A78BFA', 'icon'=>'group'],
        ['val'=>$stats['predictions'], 'label'=>'پیش‌بینی‌ها',   'color'=>'#F59E0B', 'icon'=>'analytics'],
    ]; @endphp
    @foreach($sc as $s)
    <div class="liquid-glass rounded-2xl p-4 flex items-center gap-3" style="border-right:3px solid {{ $s['color'] }}40;">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:{{ $s['color'] }}15;">
            <span class="material-symbols-outlined text-base" style="color:{{ $s['color'] }};">{{ $s['icon'] }}</span>
        </div>
        <div>
            <p class="text-xs" style="color:rgba(185,203,185,0.6);">{{ $s['label'] }}</p>
            <p class="text-2xl font-black font-heading" style="color:{{ $s['color'] }};">{{ $s['val'] }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- ══ IMPORT ══ --}}
    <div class="liquid-glass rounded-2xl overflow-hidden">
        <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(0,228,118,0.12);">
                <span class="material-symbols-outlined text-base" style="color:#00e476;">upload_file</span>
            </div>
            <div>
                <h2 class="font-black text-sm font-heading text-white">ایمپورت بازی‌ها</h2>
                <p class="text-xs" style="color:rgba(185,203,185,0.6);">فایل JSON فرمت worldcup.json</p>
            </div>
        </div>

        <div class="p-5">
            @if(session('import_errors'))
            <div class="rounded-xl px-4 py-3 mb-4 text-xs space-y-1" style="background:rgba(255,90,90,0.08);border:1px solid rgba(255,90,90,0.2);color:#FF8A8A;">
                @foreach(session('import_errors') as $err)
                <p>{{ $err }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('admin.import.games') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Drop zone --}}
                <div id="drop-zone"
                     class="rounded-2xl border-2 border-dashed flex flex-col items-center justify-center gap-3 py-10 cursor-pointer transition-all"
                     style="border-color:rgba(0,228,118,0.25);background:rgba(0,228,118,0.03);"
                     onclick="document.getElementById('json_file').click()"
                     ondragover="event.preventDefault();this.style.borderColor='#00e476';this.style.background='rgba(0,228,118,0.07)'"
                     ondragleave="this.style.borderColor='rgba(0,228,118,0.25)';this.style.background='rgba(0,228,118,0.03)'"
                     ondrop="handleDrop(event)">
                    <span class="material-symbols-outlined text-4xl" style="color:rgba(0,228,118,0.4);">cloud_upload</span>
                    <div class="text-center">
                        <p class="text-sm font-bold text-white">فایل JSON را اینجا رها کنید</p>
                        <p class="text-xs mt-1" style="color:rgba(185,203,185,0.5);">یا کلیک کنید برای انتخاب</p>
                    </div>
                    <p class="text-xs font-mono" id="file-name" style="color:#00e476;"></p>
                    <input type="file" id="json_file" name="json_file" accept=".json" class="hidden"
                           onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? ''">
                </div>

                <div class="flex items-center gap-3 p-4 rounded-xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                    <input type="checkbox" id="update_existing" name="update_existing" value="1"
                           class="w-4 h-4 rounded cursor-pointer accent-[#00e476]">
                    <div>
                        <label for="update_existing" class="text-sm font-bold text-white cursor-pointer">بروزرسانی بازی‌های موجود</label>
                        <p class="text-xs mt-0.5" style="color:rgba(185,203,185,0.5);">اگر بازی قبلاً ثبت شده، اطلاعاتش را آپدیت کند</p>
                    </div>
                </div>

                <button type="submit"
                        class="btn-primary w-full py-3.5">
                    <span class="material-symbols-outlined text-base">upload</span>
                    <span>شروع ایمپورت</span>
                </button>
            </form>

            {{-- Format hint --}}
            <div class="mt-4 p-3 rounded-xl" style="background:rgba(77,159,255,0.05);border:1px solid rgba(77,159,255,0.15);">
                <p class="text-xs font-bold mb-1" style="color:#4D9FFF;">فرمت مورد انتظار:</p>
                <pre class="text-[10px] font-mono leading-relaxed overflow-x-auto" style="color:rgba(185,203,185,0.7);">{"matches": [
  {
    "round": "Matchday 1",
    "date": "2026-06-11",
    "time": "13:00 UTC-6",
    "team1": "Mexico",
    "team2": "South Africa",
    "score": {"ft": [2, 0]},
    "group": "Group A",
    "ground": "Mexico City"
  }
]}</pre>
            </div>
        </div>
    </div>

    {{-- ══ EXPORT ══ --}}
    <div class="space-y-4">

        {{-- Export Games --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(77,159,255,0.12);">
                    <span class="material-symbols-outlined text-base" style="color:#4D9FFF;">download</span>
                </div>
                <div>
                    <h2 class="font-black text-sm font-heading text-white">اکسپورت بازی‌ها</h2>
                    <p class="text-xs" style="color:rgba(185,203,185,0.6);">همه {{ $stats['games'] }} بازی در قالب JSON</p>
                </div>
            </div>
            <div class="p-5">
                <a href="{{ route('admin.export.games') }}"
                   class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl font-bold text-sm transition-all"
                   style="background:rgba(77,159,255,0.1);border:1px solid rgba(77,159,255,0.3);color:#4D9FFF;"
                   onmouseover="this.style.background='rgba(77,159,255,0.18)'"
                   onmouseout="this.style.background='rgba(77,159,255,0.1)'">
                    <span class="material-symbols-outlined text-base">sports_soccer</span>
                    دانلود games.json
                </a>
            </div>
        </div>

        {{-- Export Leaderboard --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(245,166,35,0.12);">
                    <span class="material-symbols-outlined text-base" style="color:#F5A623;">leaderboard</span>
                </div>
                <div>
                    <h2 class="font-black text-sm font-heading text-white">اکسپورت رده‌بندی</h2>
                    <p class="text-xs" style="color:rgba(185,203,185,0.6);">لیست کامل امتیازات کاربران</p>
                </div>
            </div>
            <div class="p-5">
                <a href="{{ route('admin.export.leaderboard') }}"
                   class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl font-bold text-sm transition-all"
                   style="background:rgba(245,166,35,0.1);border:1px solid rgba(245,166,35,0.3);color:#F5A623;"
                   onmouseover="this.style.background='rgba(245,166,35,0.18)'"
                   onmouseout="this.style.background='rgba(245,166,35,0.1)'">
                    <span class="material-symbols-outlined text-base">emoji_events</span>
                    دانلود leaderboard.json
                </a>
            </div>
        </div>

        {{-- Export Per User --}}
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(167,139,250,0.12);">
                    <span class="material-symbols-outlined text-base" style="color:#A78BFA;">person_export</span>
                </div>
                <div>
                    <h2 class="font-black text-sm font-heading text-white">اکسپورت پیش‌بینی کاربر</h2>
                    <p class="text-xs" style="color:rgba(185,203,185,0.6);">همه پیش‌بینی‌های یک کاربر خاص</p>
                </div>
            </div>
            <div class="p-5 space-y-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute top-1/2 -translate-y-1/2 pointer-events-none"
                          style="right:12px;font-size:16px;color:rgba(185,203,185,0.4);">search</span>
                    <input type="text" id="user-search" placeholder="جستجوی کاربر..."
                           class="stitch-input text-sm w-full" style="padding-right:36px;"
                           oninput="filterUsers(this.value)">
                </div>

                <div class="space-y-1 max-h-52 overflow-y-auto" id="user-list">
                    @foreach($users as $u)
                    <div class="user-item flex items-center justify-between px-3 py-2.5 rounded-xl transition-all"
                         data-name="{{ strtolower($u->name) }} {{ strtolower($u->department ?? '') }}"
                         style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black font-heading flex-shrink-0"
                                 style="background:rgba(167,139,250,0.15);color:#A78BFA;">
                                {{ mb_strtoupper(mb_substr($u->name,0,1,'UTF-8')) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-white truncate">{{ $u->name }}</p>
                                @if($u->department)
                                <p class="text-[10px] truncate" style="color:rgba(185,203,185,0.5);">{{ $u->department }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.export.user.predictions', $u) }}"
                           class="flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg flex-shrink-0 transition-all"
                           style="background:rgba(167,139,250,0.1);border:1px solid rgba(167,139,250,0.25);color:#A78BFA;"
                           onmouseover="this.style.background='rgba(167,139,250,0.2)'"
                           onmouseout="this.style.background='rgba(167,139,250,0.1)'">
                            <span class="material-symbols-outlined" style="font-size:13px;">download</span>
                            JSON
                        </a>
                    </div>
                    @endforeach

                    @if($users->isEmpty())
                    <p class="text-xs text-center py-6" style="color:rgba(185,203,185,0.4);">هیچ کاربری یافت نشد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── import پیش‌بینی‌های سیستم قدیم ── --}}
<div class="mt-5 liquid-glass rounded-2xl overflow-hidden">
    <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(167,139,250,0.12);">
                <span class="material-symbols-outlined text-base" style="color:#A78BFA;">history</span>
            </div>
            <div>
                <h2 class="font-black text-sm font-heading text-white">وارد کردن پیش‌بینی‌های سیستم قدیم</h2>
                <p class="text-xs" style="color:rgba(185,203,185,0.6);">برای هر بازی، پیش‌بینی کاربران از سامانه قبلی را وارد کنید</p>
            </div>
        </div>
        <a href="{{ route('admin.import.predictions') }}"
           class="px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2 transition-all"
           style="background:rgba(167,139,250,0.12);border:1px solid rgba(167,139,250,0.3);color:#A78BFA;"
           onmouseover="this.style.background='rgba(167,139,250,0.22)'"
           onmouseout="this.style.background='rgba(167,139,250,0.12)'">
            <span class="material-symbols-outlined text-sm">add</span>
            شروع import
        </a>
    </div>
    <div class="px-5 py-4 text-xs" style="color:rgba(185,203,185,0.5);">
        <ul class="space-y-1 list-disc list-inside">
            <li>ابتدا بازی مورد نظر را انتخاب کنید</li>
            <li>سپس برای هر کاربر، پیش‌بینی (نتیجه‌ای که در سامانه قدیم زده) را وارد کنید</li>
            <li>اگر کاربر قبلاً پیش‌بینی داشت، بروزرسانی می‌شود</li>
            <li>امتیاز خودکار محاسبه می‌شود (اگر بازی نتیجه دارد)</li>
        </ul>
    </div>
</div>

@push('scripts')
<script>
function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (!file) return;
    const input = document.getElementById('json_file');
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    document.getElementById('file-name').textContent = file.name;
    e.currentTarget.style.borderColor = '#00e476';
    e.currentTarget.style.background = 'rgba(0,228,118,0.07)';
}

function filterUsers(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('#user-list .user-item').forEach(el => {
        el.style.display = (!q || el.dataset.name.includes(q)) ? '' : 'none';
    });
}
</script>
@endpush

@endsection
