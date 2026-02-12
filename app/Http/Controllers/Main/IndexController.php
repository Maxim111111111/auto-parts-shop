<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Part;

class IndexController extends Controller
{
    public function __invoke()
    {
        $categoryCount = Category::count();
        $partCount = Part::count();
        $activePartCount = Part::where('is_active', true)->count();
        $totalStock = Part::sum('stock');

        $lowStockParts = Part::query()
            ->with('category')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(5)
            ->get();

        $latestParts = Part::query()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        return view('main.index', compact(
            'categoryCount',
            'partCount',
            'activePartCount',
            'totalStock',
            'lowStockParts',
            'latestParts',
        ));
    }
}
