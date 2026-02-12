<?php

namespace App\Http\Controllers\Part;

use App\Http\Controllers\Controller;
use App\Models\Part;

class DeleteController extends Controller
{
    public function __invoke(Part $part)
    {
        $part->delete();

        return redirect()
            ->route('part.index')
            ->with('success', 'Запчасть удалена.');
    }
}
