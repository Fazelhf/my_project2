@extends('layouts.admin')

@section('title', 'مدیریت تیم‌ها')
@section('page-title', 'تیم‌ها')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm" style="color:#94A3B8;">{{ $teams->flatten()->count() }} تیم ثبت شده</p>
    <a href="{{ route('admin.teams.create') }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold cursor-pointer transition-colors"
       style="background-color:#22C55E; color:#020617;"
       onmouseover="this.style.backgroundColor='#16A34A';"
       onmouseout="this.style.backgroundColor='#22C55E';">
        + تیم جدید
    </a>
</div>

@foreach($teams->sortKeys() as $group => $groupTeams)
    <div class="mb-6">
        <h2 class="text-xs font-bold uppercase tracking-wider mb-3"
            style="color:#94A3B8;">
            @if($group) گروه {{ $group }} @else بدون گروه @endif
        </h2>
        <div class="rounded-2xl border overflow-hidden" style="background-color:#0F172A; border-color:#334155;">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #334155;">
                        <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium" style="color:#475569;">نام</th>
                        <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium hidden sm:table-cell" style="color:#475569;">نام فارسی</th>
                        <th class="px-5 py-3 text-right text-xs uppercase tracking-wider font-medium" style="color:#475569;">کد FIFA</th>
                        <th class="px-5 py-3 text-left text-xs uppercase tracking-wider font-medium" style="color:#475569;">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#334155;">
                    @foreach($groupTeams as $team)
                        <tr onmouseover="this.style.backgroundColor='#1E293B';" onmouseout="this.style.backgroundColor='';">
                            <td class="px-5 py-3 font-medium" style="color:#F8FAFC;">{{ $team->name }}</td>
                            <td class="px-5 py-3 hidden sm:table-cell" style="color:#94A3B8;">{{ $team->name_fa }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-md text-xs font-mono font-bold"
                                      style="background-color:#1E293B; color:#22C55E;">{{ $team->code }}</span>
                            </td>
                            <td class="px-5 py-3 text-left">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="text-xs font-medium transition-colors"
                                       style="color:#60a5fa;"
                                       onmouseover="this.style.color='#93c5fd';"
                                       onmouseout="this.style.color='#60a5fa';">
                                        ویرایش
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
                                          onsubmit="return confirm('آیا از حذف این تیم مطمئن هستید؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium cursor-pointer transition-colors"
                                                style="color:#f87171;"
                                                onmouseover="this.style.color='#fca5a5';"
                                                onmouseout="this.style.color='#f87171';">
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
    <div class="rounded-2xl border p-12 text-center" style="background-color:#0F172A; border-color:#334155;">
        <p class="text-sm" style="color:#475569;">هیچ تیمی ثبت نشده است.</p>
    </div>
@endif

@endsection
