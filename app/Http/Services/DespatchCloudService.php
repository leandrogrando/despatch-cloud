<?php
namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class DespatchCloudService
{
    private $url;
    private $apiKey;
    private $client;

    public function __construct($log = true)
    {
        $this->url = config('services.despatch-cloud.api_url');
        $this->apiKey = config('services.despatch-cloud.api_key');
        $this->client = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->logWhen($log);
    }

    public function getOrders($id = null, $date = null, $page = null)
    {
        $params = [
            'api_key' => $this->apiKey,
        ];

        if ($id) {
            $params['id'] = $id;
        }

        if ($date) {
            $params['date'] = $date;
        }

        if ($page) {
            $params['page'] = $page;
        }

        $response = $this->client->get($this->url . '/orders', $params)->throw();

        return $response->json();
    }

    public function getOrderDetails($order_id)
    {
        $response = $this->client->get($this->url . '/orders/' . $order_id, [
            'api_key' => $this->apiKey,
        ])->throw();

        return $response->json();
    }

    public function updateOrderType($order_id, $status)
    {
        $response = $this->client->post($this->url . '/orders/' . $order_id, [
            'api_key' => $this->apiKey,
            'type' => $status,
        ])->throw();

        return $response->json();
    }
}
