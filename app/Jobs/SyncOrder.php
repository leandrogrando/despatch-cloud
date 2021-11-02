<?php

namespace App\Jobs;

use App\Http\Services\DespatchCloudService;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\RateLimitedMiddleware\RateLimited;

class SyncOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $service;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(29)
            ->everySeconds(60)
            ->releaseAfterSeconds(90);

        return [$rateLimitedMiddleware];
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->service = new DespatchCloudService();

        // Save the customer
        $order_details = $this->service->getOrderDetails($this->order['id']);
        $customer = Customer::firstOrNew(['id' => $order_details['customer_id']]);
        $customer->fill($order_details['customer']);
        $customer->save();

        // Save the shipping address
        $shipping_address = Address::firstOrNew(['id' => $order_details['shipping_address']['id']]);
        $shipping_address->fill($order_details['shipping_address']);
        $shipping_address->save();

        // Save the billing address
        $billing_address = Address::firstOrNew(['id' => $order_details['billing_address']['id']]);
        $billing_address->fill($order_details['billing_address']);
        $billing_address->save();

        // Save the order
        $order_model = Order::firstOrNew(['id' => $this->order['id']]);
        $order_model->fill($this->order);
        $order_model->customer_id = $order_details['customer_id'];
        $order_model->shipping_address_id = $order_details['shipping_address']['id'];
        $order_model->billing_address_id = $order_details['billing_address']['id'];
        $order_model->save();

        // Save the products
        foreach ($order_details['order_items'] as $order_item) {
            $product_model = Product::firstOrNew(['id' => $order_item['product_id']]);
            $product_model->fill($order_item['product']);
            $product_model->save();

            $item_model = OrderItem::firstOrNew(['id' => $order_item['id']]);
            $item_model->fill($order_item);
            $item_model->order_id = $this->order['id'];
            $item_model->product_id = $order_item['product_id'];
            $item_model->save();
        }

        // Change order type to approved
        $order_model->update(['type' => 'approved']);
        $this->service->updateOrderType($this->order['id'], 'approved');
    }
}
