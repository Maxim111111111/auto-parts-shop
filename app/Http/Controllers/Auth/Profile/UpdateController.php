<?php

namespace App\Http\Controllers\Auth\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileUpdateRequest;

class UpdateController extends Controller
{
    public function __invoke(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return back()->with('success', 'Профиль обновлен.');
    }
}
