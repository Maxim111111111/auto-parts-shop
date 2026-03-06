<?php

namespace App\Http\Controllers\Auth\Register;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('auth.register');
    }
}
