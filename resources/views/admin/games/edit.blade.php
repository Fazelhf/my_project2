@extends('layouts.admin')

@section('title', 'ویرایش بازی')
@section('page-title', 'ویرایش: ' . $game->homeTeam->name . ' vs ' . $game->awayTeam->name)

@section('content')

<div class="max-w-xl">
    <div class="rounded-2xl border border-brand-border bg-brand-surface p-6">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl text-sm bg-red-950/50 border border-red-800/50 text-red-300 space-y-1">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.games.update', $game) }}" class="space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">تیم اول</label>
                    <select name="home_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('home_team_id', $game->home_team_id) == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ $team->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">تیم دوم</label>
                    <select name="away_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('away_team_id', $game->away_team_id) == $team->id ? 'selected' : '' }}>
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
                            <option value="{{ $key }}" {{ old('stage', $game->stage) === $key ? 'selected' : '' }}>{{ $label }}</option>
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
                            <option value="{{ $g }}" {{ old('group_name', $game->group_name) === $g ? 'selected' : '' }}>گروه {{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">زمان بازی</label>
                    <input type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', $game->scheduled_at?->format('Y-m-d\TH:i')) }}" required
                           class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                  outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">وضعیت</label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                   outline-none focus:border-brand-green transition-all">
                        @foreach(['upcoming' => 'پیش‌رو', 'live' => 'زنده', 'finished' => 'پایان یافته', 'postponed' => 'به تعویق'] as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', $game->status) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">ورزشگاه</label>
                <input type="text" name="venue" value="{{ old('venue', $game->venue) }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                              outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all">
            </div>

            <div class="flex items-center gap-3">
                <input type="hidden" name="is_disciplinary" value="0">
                <input type="checkbox" id="is_disciplinary" name="is_disciplinary" value="1"
                       {{ old('is_disciplinary', $game->is_disciplinary) ? 'checked' : '' }}
                       class="w-4 h-4 rounded cursor-pointer accent-orange-500">
                <label for="is_disciplinary" class="text-sm cursor-pointer text-brand-muted">
                    بازی انضباطی (بدون امتیازدهی)
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-brand-muted uppercase tracking-wider mb-2">یادداشت</label>
                <textarea name="notes" rows="2"
                          class="w-full px-4 py-2.5 rounded-xl text-sm bg-brand-card border border-brand-border text-brand-text
                                 outline-none focus:border-brand-green focus:ring-2 focus:ring-brand-green/20 transition-all resize-none">{{ old('notes', $game->notes) }}</textarea>
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

        {{-- Delete Button --}}
        <form id="deleteForm" method="POST" action="{{ route('admin.games.destroy', $game) }}"
              class="mt-6 pt-6 border-t border-brand-border">
            @csrf @method('DELETE')
            <button type="button" onclick="confirmDelete()"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors
                           bg-red-950/30 border border-red-800/50 text-red-400 hover:bg-red-950/50 hover:border-red-700/70">
                <span class="material-symbols-outlined text-sm align-middle">delete</span>
                حذف بازی
            </button>
            <p class="text-xs mt-2" style="color:rgba(255,90,90,0.7);">این عملیات قابل بازگشت نیست</p>
        </form>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('آیا مطمئن هستی که می‌خوای این بازی را حذف کنی؟\n\nاین عملیات قابل بازگشت نیست!')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

@endsection
