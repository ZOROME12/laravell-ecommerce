<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SquareServices
{
    protected $accessToken;
    protected $locationId;
    protected $apiUrl;

    public function __construct()
    {
        $this->accessToken = env('SQUARE_ACCESS_TOKEN');
        $this->locationId = env('SQUARE_LOCATION_ID');
        $this->apiUrl = 'https://connect.squareupsandbox.com/v2';
    }

    private function makeRequest($method, $endpoint, $data = [])
    {
        $request = Http::withHeaders([
            'Square-Version' => '2023-10-18',
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json'
        ]);

        $url = $this->apiUrl . $endpoint;

        if (strtoupper($method) === 'POST' || strtoupper($method) === 'PUT' || strtoupper($method) === 'DELETE') {
            return $request->{strtolower($method)}($url, $data);
        }
        return $request->{strtolower($method)}($url);
    }

    public function createOrUpdateProduct($product, $squareItemId = null)
    {
        $payload = [
            'idempotency_key' => (string) Str::uuid(),
            'object' => [
                'type' => 'ITEM',
                'id' => $squareItemId ?: '#' . Str::uuid(),
                'item_data' => [
                    'name' => $product->name,
                    'description' => $product->description,
                    'variations' => [
                        [
                            'type' => 'ITEM_VARIATION',
                            'id' => '#' . Str::uuid(),
                            'item_variation_data' => [
                                'name' => 'Regular',
                                'pricing_type' => 'FIXED_PRICING',
                                'price_money' => [
                                    'amount' => $product->price * 100,
                                    'currency' => 'USD'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', '/catalog/object', $payload);

        if (!$response->successful()) {
            Log::error('Square API Error (createOrUpdateProduct): ' . $response->body());
            return null;
        }

        $squareObject = $response->json()['catalog_object'];
        return [
            'item_id' => $squareObject['id'],
            'variation_id' => $squareObject['item_data']['variations'][0]['id']
        ];
    }

    public function setInventory($squareVariationId, $quantity)
    {
        $payload = [
            'idempotency_key' => (string) Str::uuid(),
            'changes' => [
                [
                    'type' => 'PHYSICAL_COUNT',
                    'physical_count' => [
                        'catalog_object_id' => $squareVariationId,
                        'state' => 'IN_STOCK',
                        'location_id' => $this->locationId,
                        'quantity' => (string) $quantity,
                        'occurred_at' => now()->toIso8601String()
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', '/inventory/changes/batch-create', $payload);

        if (!$response->successful()) {
            Log::error('Square API Error (setInventory): ' . $response->body());
        }

        return $response->json();
    }
    
    public function adjustInventory($squareVariationId, $quantityChange)
    {
        $quantity = abs($quantityChange);

        $payload = [
            'idempotency_key' => (string) Str::uuid(),
            'changes' => [
                [
                    'type' => 'ADJUSTMENT',
                    'adjustment' => [
                        'catalog_object_id' => $squareVariationId,
                        'from_state' => 'IN_STOCK',
                        'to_state' => 'SOLD',
                        'location_id' => $this->locationId,
                        'quantity' => (string) $quantity,
                        'occurred_at' => now()->toIso8601String()
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', '/inventory/changes/batch-create', $payload);

        if (!$response->successful()) {
            Log::error('Square API Error (adjustInventory): ' . $response->body());
        }

        return $response->json();
    }

    public function createOrder(array $payload)
    {
        $response = $this->makeRequest('POST', '/orders', $payload);
        if (!$response->successful()) {
            Log::error('Square API Error (createOrder): ' . $response->body());
        }
        return $response->json();
    }
    /**
     * Deletes a product (catalog object) from Square.
     */
    public function deleteProduct($squareItemId)
    {
        $payload = [
            'object_ids' => [$squareItemId]
        ];
        $response = $this->makeRequest('POST', '/catalog/batch-delete', $payload);
        if (!$response->successful()) {
            Log::error('Square API Error (deleteProduct): ' . $response->body());
        }
        return $response->json();
    }
}
