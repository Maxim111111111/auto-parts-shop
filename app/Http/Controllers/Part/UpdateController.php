<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Http\Requests\Part\UpdateRequest;
use App\Models\Part;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Part $part)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $part->update($data);

        return redirect()
            ->route('part.show', $part)
            ->with('success', 'Запчасть обновлена.');
    }
}
