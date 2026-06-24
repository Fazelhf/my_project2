<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $teams = Team::ordered()->get()->groupBy('group_name');

        return view('admin.teams.index', compact('teams'));
    }

    public function create(): View
    {
        return view('admin.teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'name_fa'    => ['nullable', 'string', 'max:100'],
            'code'       => ['required', 'string', 'size:3', 'unique:teams,code', 'uppercase'],
            'group_name' => ['nullable', 'string', 'size:1', 'uppercase', Rule::in(['A','B','C','D','E','F','G','H'])],
            'flag_url'   => ['nullable', 'url', 'max:500'],
        ], [
            'name.required'  => 'نام تیم الزامی است.',
            'code.required'  => 'کد FIFA الزامی است.',
            'code.size'      => 'کد FIFA باید دقیقاً ۳ کاراکتر باشد (مثل BRA).',
            'code.unique'    => 'این کد FIFA قبلاً ثبت شده است.',
            'group_name.in'  => 'گروه باید بین A تا H باشد.',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        if (isset($validated['group_name'])) {
            $validated['group_name'] = strtoupper($validated['group_name']);
        }

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', "تیم {$validated['name']} با موفقیت اضافه شد.");
    }

    public function edit(Team $team): View
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'name_fa'    => ['nullable', 'string', 'max:100'],
            'code'       => ['required', 'string', 'size:3', Rule::unique('teams', 'code')->ignore($team->id), 'uppercase'],
            'group_name' => ['nullable', 'string', 'size:1', 'uppercase', Rule::in(['A','B','C','D','E','F','G','H'])],
            'flag_url'   => ['nullable', 'url', 'max:500'],
        ]);

        $validated['code'] = strtoupper($validated['code']);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', "تیم {$team->name} به‌روز شد.");
    }

    public function destroy(Team $team): RedirectResponse
    {
        // تیمی که بازی دارد حذف نمی‌شود
        if ($team->homeGames()->exists() || $team->awayGames()->exists()) {
            return back()->with('error', 'این تیم دارای بازی ثبت‌شده است و قابل حذف نیست.');
        }

        $name = $team->name;
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', "تیم {$name} حذف شد.");
    }
}
