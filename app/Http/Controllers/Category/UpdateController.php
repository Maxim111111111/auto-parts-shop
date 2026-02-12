<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('category.show', $category)
            ->with('success', 'Категория обновлена.');
    }
}
