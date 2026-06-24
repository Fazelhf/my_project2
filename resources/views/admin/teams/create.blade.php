@extends('layouts.admin')

@section('title', 'تیم جدید')
@section('page-title', 'تیم جدید')

@section('content')

<div class="max-w-lg">
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-6">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl text-sm bg-red-950/50 border border-red-800/50 text-red-300 space-y-1">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.teams.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">نام انگلیسی</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">نام فارسی</label>
                <input type="text" name="name_fa" value="{{ old('name_fa') }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">کد FIFA (۳ حرف)</label>
                <input type="text" name="code" value="{{ old('code') }}" maxlength="3" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm font-mono uppercase bg-brand-card border border-brand-border text-brand-green
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">گروه (A تا H)</label>
                <select name="group_name"
                        class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                               outline-none focus:border-brand-green transition-all">
                    <option value="">— بدون گروه —</option>
                    @foreach(['A','B','C','D','E','F','G','H'] as $g)
                        <option value="{{ $g }}" {{ old('group_name') === $g ? 'selected' : '' }}>گروه {{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">آدرس پرچم (URL)</label>
                <input type="url" name="flag_url" value="{{ old('flag_url') }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors
                               bg-brand-green hover:bg-brand-green-dim text-black">
                    ذخیره
                </button>
                <a href="{{ route('admin.teams.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors
                          bg-brand-card border border-brand-border text-brand-muted hover:text-brand-text">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
