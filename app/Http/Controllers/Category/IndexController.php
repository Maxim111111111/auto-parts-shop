<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;

class IndexController extends Controller
{
    public function __invoke()
    {
        $search = trim((string) request('search'));

        $categories = Category::query()
            ->withCount('parts')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('category.index', compact('categories', 'search'));
    }
}
