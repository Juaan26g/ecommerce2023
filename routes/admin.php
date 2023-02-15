<?php
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\{ShowProducts,EditProduct,CreateProduct,ShowCategory,BrandComponent};
use App\Http\Controllers\Admin\{ProductController, CategoryController};

Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');
Route::get('products/create', CreateProduct::class)->name('admin.products.create');
Route::post('product/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');
Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('categories/{category}', ShowCategory::class)->name('admin.categories.show');
Route::get('brands', BrandComponent::class)->name('admin.brands.index');