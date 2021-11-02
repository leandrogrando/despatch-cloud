<?php

namespace App\Console\Commands;

use App\Http\Services\DespatchCloudService;
use App\Jobs\SyncOrder;
use App\Jobs\SyncOrders;
use App\Models\Order;
use Illuminate\Console\Command;

class SyncOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes API order list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        SyncOrders::dispatch();
        $this->info('Synchronization of orders sent to queue');

        return Command::SUCCESS;
    }
}
