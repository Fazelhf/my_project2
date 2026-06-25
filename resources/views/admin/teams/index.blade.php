@extends('layouts.admin')

@section('title', 'مدیریت تیم‌ها')
@section('page-title', 'تیم‌ها')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm font-mono" style="color:rgba(185,203,185,0.6);">{{ $teams->flatten()->count() }} تیم ثبت شده</p>
    <a href="{{ route('admin.teams.create') }}"
       class="px-5 py-2.5 rounded-xl text-sm font-bold cursor-pointer transition-all flex items-center gap-2"
       style="background:#00e476;color:#003919;"
       onmouseover="this.style.boxShadow='0 0 20px rgba(0,228,118,0.4)'"
       onmouseout="this.style.boxShadow=''">
        <span class="material-symbols-outlined text-base">add</span>
        تیم جدید
    </a>
</div>

@foreach($teams->sortKeys() as $group => $groupTeams)
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-2.5 h-2.5 rounded-full" style="background:#00e476;box-shadow:0 0 6px #00e476;"></div>
            <h2 class="text-xs font-bold uppercase tracking-wider" style="color:#00e476;">
                @if($group) گروه {{ $group }} @else بدون گروه @endif
            </h2>
        </div>
        <div class="liquid-glass rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.02);">
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">نام</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider hidden sm:table-cell" style="color:rgba(185,203,185,0.6);">نام فارسی</th>
                        <th class="px-5 py-3 text-right text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">کد FIFA</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-wider" style="color:rgba(185,203,185,0.6);">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupTeams as $team)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.05);"
                            onmouseover="this.style.background='rgba(0,228,118,0.03)'"
                            onmouseout="this.style.background=''">
                            <td class="px-5 py-3 font-medium text-white">{{ $team->name }}</td>
                            <td class="px-5 py-3 hidden sm:table-cell" style="color:rgba(185,203,185,0.7);">{{ $team->name_fa }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-md text-xs font-mono font-bold"
                                      style="background:rgba(0,228,118,0.1);color:#00e476;border:1px solid rgba(0,228,118,0.2);">
                                    {{ $team->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-left">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="text-xs font-bold transition-colors" style="color:#4D9FFF;"
                                       onmouseover="this.style.color='#93c5fd'" onmouseout="this.style.color='#4D9FFF'">
                                        ویرایش
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
                                          onsubmit="return confirm('آیا از حذف این تیم مطمئن هستید؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-bold cursor-pointer transition-colors" style="color:#FF8A8A;"
                                                onmouseover="this.style.color='#fca5a5'" onmouseout="this.style.color='#FF8A8A'">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

@if($teams->isEmpty())
    <div class="liquid-glass rounded-2xl p-12 text-center">
        <span class="material-symbols-outlined text-4xl mb-3 block" style="color:rgba(0,228,118,0.3);">flag</span>
        <p class="text-sm" style="color:rgba(185,203,185,0.5);">هیچ تیمی ثبت نشده است.</p>
    </div>
@endif

@endsection
