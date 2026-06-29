<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile', [
            'user' => [
                'id'         => $request->user()->id,
                'name'       => $request->user()->name,
                'email'      => $request->user()->email,
                'department' => $request->user()->department,
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'پروفایل با موفقیت به‌روز شد.');
    }
}
