<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class ShowController extends Controller
{
    public function __invoke(Category $category)
    {
        $category->load(['parts' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('category.show', compact('category'));
    }
}
