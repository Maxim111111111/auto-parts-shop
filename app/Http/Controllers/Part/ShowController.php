<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Models\Part;

class ShowController extends Controller
{
    public function __invoke(Part $part)
    {
        $part->load('category');

        return view('part.show', compact('part'));
    }
}
