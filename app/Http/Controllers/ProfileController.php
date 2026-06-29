<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('user.profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'min:3', 'max:50', 'alpha_num', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'username.alpha_num' => 'نام کاربری فقط می‌تواند شامل حروف و عدد باشد.',
            'username.min'       => 'نام کاربری باید حداقل ۳ کاراکتر باشد.',
            'username.unique'    => 'این نام کاربری قبلاً استفاده شده است.',
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['username'])) {
            $user->username = strtolower($data['username']);
        }
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'پروفایل با موفقیت به‌روز شد.');
    }
}
