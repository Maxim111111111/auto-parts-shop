<?php

namespace App\Http\Controllers\Auth\Register;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::query()->create($request->validated());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Аккаунт успешно создан.');
    }
}
