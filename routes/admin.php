<?php
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\{ShowProducts,EditProduct,CreateProduct};
use App\Http\Controllers\Admin\ProductController;

Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');
Route::get('products/create', CreateProduct::class)->name('admin.products.create');
Route::post('product/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');