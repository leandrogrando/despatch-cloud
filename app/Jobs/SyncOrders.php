<?php

namespace App\Jobs;

use App\Http\Services\DespatchCloudService;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\RateLimitedMiddleware\RateLimited;

class SyncOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $service;

    public function retryUntil(): \DateTime
    {
        return now()->addDay();
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
        ->allow(1)
        ->everySeconds(60)
        ->releaseAfterSeconds(90);

        return [$rateLimitedMiddleware];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->service = new DespatchCloudService();

        $last_order_id = Order::max('id') ?? 0;

        $orders = $this->service->getOrders($last_order_id);

        foreach ($orders['data'] as $order) {
            SyncOrder::dispatch($order);
        }
    }
}
