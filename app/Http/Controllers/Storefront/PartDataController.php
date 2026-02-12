<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\JsonResponse;

class PartDataController extends Controller
{
    public function __invoke(Part $part): JsonResponse
    {
        abort_unless($part->is_active, 404);

        $part->load('category:id,title');

        $related = Part::query()
            ->where('is_active', true)
            ->where('id', '!=', $part->id)
            ->when(
                $part->category_id,
                fn ($query) => $query->where('category_id', $part->category_id),
                fn ($query) => $query->whereNull('category_id'),
            )
            ->latest()
            ->limit(4)
            ->get(['id', 'sku', 'name', 'price', 'stock']);

        return response()->json([
            'part' => [
                'id' => $part->id,
                'sku' => $part->sku,
                'name' => $part->name,
                'brand' => $part->brand,
                'category' => $part->category?->title,
                'price' => (float) $part->price,
                'stock' => $part->stock,
                'description' => $part->description,
            ],
            'related' => $related->map(static function (Part $item): array {
                return [
                    'id' => $item->id,
                    'sku' => $item->sku,
                    'name' => $item->name,
                    'price' => (float) $item->price,
                    'stock' => $item->stock,
                ];
            }),
        ]);
    }
}
