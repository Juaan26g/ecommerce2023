<?php
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\{ShowProducts,};

Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');
Route::get('products/create', CreateProduct::class)->name('admin.products.create');