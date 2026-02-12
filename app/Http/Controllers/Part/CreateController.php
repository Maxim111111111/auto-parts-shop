<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CreateController extends Controller
{
    public function __invoke()
    {
        $categories = Category::query()->orderBy('title')->get(['id', 'title']);

        return view('part.create', compact('categories'));
    }
}
