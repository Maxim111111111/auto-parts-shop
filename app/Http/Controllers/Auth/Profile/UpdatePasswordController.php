<?php

namespace App\Http\Controllers\Auth\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordUpdateRequest;

class UpdatePasswordController extends Controller
{
    public function __invoke(PasswordUpdateRequest $request)
    {
        $request->user()->update([
            'password' => $request->validated('password'),
        ]);

        return back()->with('success', 'Пароль обновлен.');
    }
}
