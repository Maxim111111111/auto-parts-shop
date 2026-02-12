<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Part;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $parts = Part::query()
            ->with('category:id,title')
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->get([
                'id',
                'category_id',
                'sku',
                'name',
                'brand',
                'price',
                'stock',
                'description',
            ]);

        $categories = Category::query()
            ->whereHas('parts', fn ($query) => $query->where('is_active', true))
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json([
            'categories' => $categories,
            'parts' => $parts->map(static function (Part $part): array {
                return [
                    'id' => $part->id,
                    'category_id' => $part->category_id,
                    'sku' => $part->sku,
                    'name' => $part->name,
                    'brand' => $part->brand,
                    'price' => (float) $part->price,
                    'stock' => $part->stock,
                    'description' => $part->description,
                    'category' => $part->category?->title,
                ];
            }),
        ]);
    }
}
