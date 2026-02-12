<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('storefront.spa');
    }
}
