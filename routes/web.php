<?php

use App\Http\Services\DespatchCloudService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $service = new DespatchCloudService();
    $orders = $service->getOrders();
    dd($service->getOrderDetails($orders['data'][0]['id']));
    // return view('welcome');
});
