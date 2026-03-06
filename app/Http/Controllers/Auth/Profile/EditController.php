<?php

namespace App\Http\Controllers\Auth\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
}
