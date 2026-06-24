@extends('layouts.admin')

@section('title', 'مدیریت تیم‌ها')
@section('page-title', 'تیم‌ها')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-brand-muted">{{ $teams->flatten()->count() }} تیم ثبت شده</p>
    <a href="{{ route('admin.teams.create') }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold cursor-pointer transition-colors
              bg-brand-green hover:bg-brand-green-dim text-black">
        + تیم جدید
    </a>
</div>

@foreach($teams->sortKeys() as $group => $groupTeams)
    <div class="mb-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-brand-muted mb-3">
            @if($group) گروه {{ $group }} @else بدون گروه @endif
        </h2>
        <div class="rounded-2xl border border-brand-border bg-brand-surface overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-brand-border">
                        <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">نام</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider hidden sm:table-cell">نام فارسی</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-brand-subtle uppercase tracking-wider">کد FIFA</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-brand-subtle uppercase tracking-wider">عملیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-border">
                    @foreach($groupTeams as $team)
                        <tr class="hover:bg-brand-card transition-colors duration-100">
                            <td class="px-5 py-3 font-medium text-brand-text">{{ $team->name }}</td>
                            <td class="px-5 py-3 hidden sm:table-cell text-brand-muted">{{ $team->name_fa }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-md text-xs font-mono font-bold bg-brand-card text-brand-green">
                                    {{ $team->code }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-left">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.teams.edit', $team) }}"
                                       class="text-xs font-medium text-brand-blue hover:text-blue-300 transition-colors">
                                        ویرایش
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
                                          onsubmit="return confirm('آیا از حذف این تیم مطمئن هستید؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs font-medium cursor-pointer text-brand-red hover:text-red-300 transition-colors">
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
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-12 text-center">
        <p class="text-sm text-brand-subtle">هیچ تیمی ثبت نشده است.</p>
    </div>
@endif

@endsection
