<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Services\SquareServices;
use App\Models\Product;

class SquareController extends Controller
{
    protected $squareService;

    public function __construct(SquareServices $squareService)
    {
        $this->squareService = $squareService;
    }

       public function getProducts(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $apiUrl = 'https://connect.squareup.com/v2/catalog/list?types=ITEM';

        $response = Http::withHeaders([
            'Square-Version' => '2023-10-18',
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->get($apiUrl);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to fetch products from Square.',
                'details' => $response->json()
            ], 500);
        }

        $squareObjects = $response->json()['objects'] ?? [];
        
        // **THE FIX IS HERE:**
        // 1. Get all variation IDs from the Square products.
        $variationIds = [];
        foreach ($squareObjects as $object) {
            if (isset($object['item_data']['variations'])) {
                foreach ($object['item_data']['variations'] as $variation) {
                    $variationIds[] = $variation['id'];
                }
            }
        }

        if (empty($variationIds)) {
            return response()->json(['objects' => []]);
        }

        // 2. Find all matching products in your local MySQL database.
        $localProducts = Product::whereIn('square_variation_id', $variationIds)
                                ->get()
                                ->keyBy('square_variation_id');

        // 3. Add your local image_url to the data from Square.
        foreach ($squareObjects as &$object) {
            if (isset($object['item_data']['variations'])) {
                $variationId = $object['item_data']['variations'][0]['id'];
                if (isset($localProducts[$variationId])) {
                    $localProduct = $localProducts[$variationId];
                    // Add the full, accessible image URL to the response
                    $object['image_url'] = $localProduct->image ? asset('storage/' . $localProduct->image) : null;
                } else {
                    $object['image_url'] = null;
                }
            }
        }
        
        return response()->json(['objects' => $squareObjects]);
    }

    public function recordSale(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $locationId = env('SQUARE_LOCATION_ID');
        $apiUrl = 'https://connect.squareup.com/v2/orders';

        if (!$locationId) {
            return response()->json(['error' => 'Square Location ID is not set in the .env file.'], 500);
        }

        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'totalAmount' => 'required|numeric|min:0',
        ]);

        $lineItems = [];
        foreach ($validated['items'] as $item) {
            $lineItems[] = [
                'quantity' => (string) $item['quantity'],
                'catalog_object_id' => $item['id']
            ];
        }

        $totalAmountInCents = round($validated['totalAmount'] * 100);
        $tenders = [
            [
                'type' => 'CASH',
                'amount_money' => [
                    'amount' => $totalAmountInCents,
                    'currency' => 'PHP' 
                ],
                'note' => 'Paid in cash at POS (Sandbox Test)'
            ]
        ];

        $orderPayload = [
            'order' => [
                'location_id' => $locationId,
                'line_items' => $lineItems,
                'tenders' => $tenders,
            ],
            'idempotency_key' => (string) Str::uuid()
        ];
        
        $response = Http::withHeaders([
            'Square-Version' => '2023-10-18',
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post($apiUrl, $orderPayload);

        if ($response->successful()) {
            // **THE FIX IS HERE:** This loop now explicitly adjusts inventory after the sale.
            foreach ($validated['items'] as $item) {
                $this->squareService->adjustInventory(
                    $item['id'], // This is the square_variation_id
                    $item['quantity']
                );
            }
            return $response->json();
        }

        $errorDetails = $response->json()['errors'][0]['detail'] ?? 'An unknown error occurred.';
        return response()->json([
            'error' => 'Failed to record sale with Square.',
            'details' => $errorDetails
        ], 500);
    }
    
    public function getSalesHistory(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $locationId = env('SQUARE_LOCATION_ID');
        $apiUrl = 'https://connect.squareup.com/v2/orders/search';

        $payload = [
            'location_ids' => [$locationId],
            'query' => [
                'sort' => [
                    'sort_field' => 'CREATED_AT',
                    'sort_order' => 'DESC'
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Square-Version' => '2023-10-18',
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['error' => 'Failed to fetch sales history.'], 500);
    }

    public function getInventory(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $apiUrl = 'https://connect.squareup.com/v2';

        $catalogResponse = Http::withHeaders([
            'Square-Version' => '2023-10-18', 'Authorization' => 'Bearer ' . $accessToken
        ])->get($apiUrl . '/catalog/list?types=ITEM');

        if (!$catalogResponse->successful()) {
            return response()->json(['error' => 'Failed to fetch catalog for inventory.'], 500);
        }

        $catalogObjects = $catalogResponse->json()['objects'] ?? [];
        $catalogObjectIds = [];
        foreach ($catalogObjects as $item) {
            foreach ($item['item_data']['variations'] as $variation) {
                $catalogObjectIds[] = $variation['id'];
            }
        }

        if (empty($catalogObjectIds)) {
            return response()->json(['inventory' => []]);
        }

        $inventoryResponse = Http::withHeaders([
            'Square-Version' => '2023-10-18', 'Authorization' => 'Bearer ' . $accessToken
        ])->post($apiUrl . '/inventory/batch-retrieve-counts', ['catalog_object_ids' => $catalogObjectIds]);
        
        if ($inventoryResponse->successful()) {
            $inventoryCounts = [];
            foreach ($inventoryResponse->json()['counts'] ?? [] as $count) {
                $inventoryCounts[$count['catalog_object_id']] = $count;
            }

            $localProducts = Product::whereIn('square_variation_id', $catalogObjectIds)->get()->keyBy('square_variation_id');

            $results = [];
            foreach ($catalogObjects as $item) {
                foreach ($item['item_data']['variations'] as $variation) {
                    $variationId = $variation['id'];
                    $localProduct = $localProducts->get($variationId);
                    $results[] = [
                        'name' => $item['item_data']['name'],
                        'state' => $inventoryCounts[$variationId]['state'] ?? 'NONE',
                        'quantity' => $inventoryCounts[$variationId]['quantity'] ?? 'N/A',
                        'local_id' => $localProduct ? $localProduct->id : null,
                    ];
                }
            }
            return response()->json(['inventory' => $results]);
        }
        
        return response()->json(['error' => 'Failed to fetch inventory counts.'], 500);
    }
}

