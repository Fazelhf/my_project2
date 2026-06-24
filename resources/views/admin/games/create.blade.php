@extends('layouts.admin')

@section('title', 'بازی جدید')
@section('page-title', 'بازی جدید')

@section('content')

<div class="max-w-xl">
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-6">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl text-sm bg-red-950/50 border border-red-800/50 text-red-300 space-y-1">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.games.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">تیم اول (خانگی)</label>
                    <select name="home_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        <option value="">انتخاب کنید</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ $team->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">تیم دوم (مهمان)</label>
                    <select name="away_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        <option value="">انتخاب کنید</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ $team->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">مرحله</label>
                    <select name="stage" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        @foreach(\App\Models\Game::STAGES as $key => $label)
                            <option value="{{ $key }}" {{ old('stage') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">گروه</label>
                    <select name="group_name"
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        <option value="">— —</option>
                        @foreach(['A','B','C','D','E','F','G','H'] as $g)
                            <option value="{{ $g }}" {{ old('group_name') === $g ? 'selected' : '' }}>گروه {{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">شماره بازی</label>
                    <input type="number" name="match_number" value="{{ old('match_number') }}" min="1" max="64"
                           class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                  outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">زمان بازی</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                           class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                  outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">ورزشگاه</label>
                <input type="text" name="venue" value="{{ old('venue') }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors
                               bg-brand-green hover:bg-brand-green-dim text-black">
                    ذخیره
                </button>
                <a href="{{ route('admin.games.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors
                          bg-brand-card border border-brand-border text-brand-muted hover:text-brand-text">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
