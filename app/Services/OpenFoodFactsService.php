<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenFoodFactsService
{
    public function search(string $query, int $limit = 10): array
    {
        info('SERVICE CALLED with query: ' . $query);

        $response = Http::withOptions([
            'force_ip_resolve' => 'v4',
        ])->get("https://world.openfoodfacts.net/cgi/search.pl", [
            'search_terms'   => $query,
            'search_simple'  => 1,
            'action'         => 'process',
            'json'           => 1,
            'page_size'      => $limit,
            'fields'         => 'product_name,brands,nutriments,code',
        ]);

        if ($response->failed()) {
            info('OpenFoodFacts FAILED: ' . $response->status());
            return [];
        }

        $products = $response->json('products') ?? [];

        info('SERVICE RESPONSE LENGTH: ' . count($products));

        return collect($products)
            ->filter(fn ($p) => isset($p['product_name'], $p['nutriments']))
            ->map(function ($p) {
                return [
                    'id'        => $p['code'],    // barcode
                    'name'      => $p['product_name'],
                    'brand'     => $p['brands'] ?? null,
                    'nutrients' => [
                        'calories_100g' => $p['nutriments']['energy-kcal_100g'] ?? 0,
                        'protein_100g'  => $p['nutriments']['proteins_100g'] ?? 0,
                        'carbs_100g'    => $p['nutriments']['carbohydrates_100g'] ?? 0,
                        'fats_100g'     => $p['nutriments']['fat_100g'] ?? 0,
                    ],
                ];
            })
            ->values()
            ->toArray();
    }
}
