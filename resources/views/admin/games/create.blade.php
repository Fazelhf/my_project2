@extends('layouts.admin')

@section('title', 'بازی جدید')
@section('page-title', 'بازی جدید')

@section('content')

<div class="max-w-xl">
    <div class="rounded-2xl border p-6" style="background-color:#0F172A; border-color:#334155;">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-lg text-sm" style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.games.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">تیم اول (خانگی)</label>
                    <select name="home_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                            style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;">
                        <option value="">انتخاب کنید</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->name }} ({{ $team->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">تیم دوم (مهمان)</label>
                    <select name="away_team_id" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                            style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;">
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
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">مرحله</label>
                    <select name="stage" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                            style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;">
                        @foreach(\App\Models\Game::STAGES as $key => $label)
                            <option value="{{ $key }}" {{ old('stage') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">گروه</label>
                    <select name="group_name"
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                            style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;">
                        <option value="">— —</option>
                        @foreach(['A','B','C','D','E','F','G','H'] as $g)
                            <option value="{{ $g }}" {{ old('group_name') === $g ? 'selected' : '' }}>گروه {{ $g }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">شماره بازی</label>
                    <input type="number" name="match_number" value="{{ old('match_number') }}" min="1" max="64"
                           class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                           style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                           onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">زمان بازی</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                           class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                           style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                           onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">ورزشگاه</label>
                <input type="text" name="venue" value="{{ old('venue') }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                       style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors"
                        style="background-color:#22C55E; color:#020617;"
                        onmouseover="this.style.backgroundColor='#16A34A';"
                        onmouseout="this.style.backgroundColor='#22C55E';">
                    ذخیره
                </button>
                <a href="{{ route('admin.games.index') }}"
                   class="px-5 py-2.5 rounded-xl text-sm font-semibold cursor-pointer transition-colors"
                   style="background-color:#1E293B; color:#94A3B8; border:1px solid #334155;"
                   onmouseover="this.style.color='#F8FAFC';"
                   onmouseout="this.style.color='#94A3B8';">
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
