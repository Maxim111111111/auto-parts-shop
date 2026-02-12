<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Models\Category;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $category = Category::create($request->validated());

        return redirect()
            ->route('category.show', $category)
            ->with('success', 'Категория создана.');
    }
}
