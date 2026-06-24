@extends('layouts.admin')

@section('title', 'ویرایش تیم')
@section('page-title', 'ویرایش تیم: ' . $team->name)

@section('content')

<div class="max-w-lg">
    <div class="rounded-2xl border p-6" style="background-color:#0F172A; border-color:#334155;">

        @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-lg text-sm" style="background-color:#450a0a; color:#fca5a5; border:1px solid #dc2626;">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.teams.update', $team) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">نام انگلیسی</label>
                <input type="text" name="name" value="{{ old('name', $team->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                       style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">نام فارسی</label>
                <input type="text" name="name_fa" value="{{ old('name_fa', $team->name_fa) }}"
                       class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                       style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;"
                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">کد FIFA (۳ حرف)</label>
                <input type="text" name="code" value="{{ old('code', $team->code) }}" maxlength="3" required
                       class="w-full px-4 py-2.5 rounded-xl text-sm outline-none font-mono uppercase"
                       style="background-color:#1E293B; border:1px solid #334155; color:#22C55E;"
                       onfocus="this.style.borderColor='#22C55E';" onblur="this.style.borderColor='#334155';">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">گروه (A تا H)</label>
                <select name="group_name"
                        class="w-full px-4 py-2.5 rounded-xl text-sm outline-none"
                        style="background-color:#1E293B; border:1px solid #334155; color:#F8FAFC;">
                    <option value="">— بدون گروه —</option>
                    @foreach(['A','B','C','D','E','F','G','H'] as $g)
                        <option value="{{ $g }}" {{ old('group_name', $team->group_name) === $g ? 'selected' : '' }}>گروه {{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#94A3B8;">آدرس پرچم (URL)</label>
                <input type="url" name="flag_url" value="{{ old('flag_url', $team->flag_url) }}"
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
                <a href="{{ route('admin.teams.index') }}"
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
