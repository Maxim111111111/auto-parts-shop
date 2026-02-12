<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Part;

class IndexController extends Controller
{
    public function __invoke()
    {
        $search = trim((string) request('search'));
        $status = request('status');
        $categoryId = request('category_id');

        $parts = Part::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::query()->orderBy('title')->get(['id', 'title']);

        return view('part.index', compact('parts', 'categories', 'search', 'status', 'categoryId'));
    }
}
