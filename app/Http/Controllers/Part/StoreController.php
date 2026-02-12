<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\StoreRequest;
use App\Models\Part;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $part = Part::create($data);

        return redirect()
            ->route('part.show', $part)
            ->with('success', 'Запчасть добавлена.');
    }
}
