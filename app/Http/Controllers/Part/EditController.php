<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Part;

class EditController extends Controller
{
    public function __invoke(Part $part)
    {
        $categories = Category::query()->orderBy('title')->get(['id', 'title']);

        return view('part.edit', compact('part', 'categories'));
    }
}
