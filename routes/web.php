<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{WelcomeController, CategoryController, ProductsController, SearchController, OrderController};
use App\Http\Livewire\{ShoppingCart, CreateOrder, PaymentOrder};


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

Route::get('/', WelcomeController::class);

Route::get('search', SearchController::class)->name('search');

Route::get('shopping-cart', ShoppingCart::class)->name('shopping-cart');

Route::get('products/{product}', [ProductsController::class, 'show'])->name('products.show');

Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::middleware(['auth'])->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/create', CreateOrder::class)->name('orders.create');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('orders/{order}/payment', PaymentOrder::class)->name('orders.payment');
});

Route::get('prueba', function () {
    $orders = \App\Models\Order::where('status', 1)->where('created_at', '<', now()->subMinutes(10))->get();
    foreach ($orders as $order) {
        $items = json_decode($order->content);
        foreach ($items as $item) {
            increase($item);
        }
        $order->status = 5;
        $order->save();
    }
    return "Completado con éxito";
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
