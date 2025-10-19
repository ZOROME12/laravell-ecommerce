<?php
// ========== REPLACE THE ENTIRE FILE: app/Http/Controllers/SquareController.php ==========

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SquareController extends Controller
{
    /**
     * Fetch all catalog items from the Square API.
     */
    public function getProducts(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $apiUrl = 'https://connect.squareupsandbox.com/v2/catalog/list?types=ITEM';

        $response = Http::withHeaders([
            'Square-Version' => '2023-10-18',
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json'
        ])->get($apiUrl);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'error' => 'Failed to fetch products from Square.',
            'details' => $response->json()
        ], 500);
    }

    /**
     * Create a new order in Square with a cash tender.
     */
    public function recordSale(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $locationId = env('SQUARE_LOCATION_ID');
        $apiUrl = 'https://connect.squareupsandbox.com/v2/orders';

        if (!$locationId) {
            return response()->json([
                'error' => 'Square Location ID is not set in the .env file.'
            ], 500);
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
                    'currency' => 'USD' // Currency set to USD for Sandbox
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
            return $response->json();
        }

        $errorDetails = $response->json()['errors'][0]['detail'] ?? 'An unknown error occurred with the Square API.';
        return response()->json([
            'error' => 'Failed to record sale with Square.',
            'details' => $errorDetails
        ], 500);
    }

    /**
     * Fetch recent orders from the Square API.
     */
    public function getSalesHistory(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $locationId = env('SQUARE_LOCATION_ID');
        $apiUrl = 'https://connect.squareupsandbox.com/v2/orders/search';

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

    /**
     * Fetch inventory counts for all catalog items.
     */
    public function getInventory(Request $request)
    {
        $accessToken = env('SQUARE_ACCESS_TOKEN');
        $apiUrl = 'https://connect.squareupsandbox.com/v2/inventory/counts/batch-retrieve';

        $catalogResponse = Http::withHeaders([
            'Square-Version' => '2023-10-18', 'Authorization' => 'Bearer ' . $accessToken
        ])->get('https://connect.squareupsandbox.com/v2/catalog/list?types=ITEM');

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
        ])->post($apiUrl, ['catalog_object_ids' => $catalogObjectIds]);
        
        if ($inventoryResponse->successful()) {
            $inventoryCounts = [];
            foreach ($inventoryResponse->json()['counts'] ?? [] as $count) {
                $inventoryCounts[$count['catalog_object_id']] = $count;
            }

            $results = [];
            foreach ($catalogObjects as $item) {
                foreach ($item['item_data']['variations'] as $variation) {
                    $results[] = [
                        'name' => $item['item_data']['name'],
                        'state' => $inventoryCounts[$variation['id']]['state'] ?? 'NONE',
                        'quantity' => $inventoryCounts[$variation['id']]['quantity'] ?? 'N/A',
                    ];
                }
            }
            return response()->json(['inventory' => $results]);
        }
        
        return response()->json(['error' => 'Failed to fetch inventory counts.'], 500);
    }
}