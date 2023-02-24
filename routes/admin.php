<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\{ShowProducts, EditProduct, CreateProduct, ShowCategory, BrandComponent, DepartmentComponent, ShowDepartment, ShowCity , UserComponent, ShowProducts2};
use App\Http\Controllers\Admin\{ProductController, CategoryController,OrderController};

Route::get('/', ShowProducts::class)->name('admin.index');

//añadimos la ruta 
Route::get('/dos', ShowProducts2::class)->name('admin.index2');
//
Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');
Route::get('products/create', CreateProduct::class)->name('admin.products.create');
Route::post('product/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');
Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('categories/{category}', ShowCategory::class)->name('admin.categories.show');
Route::get('brands', BrandComponent::class)->name('admin.brands.index');
Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
Route::get('departments', DepartmentComponent::class)->name('admin.departments.index');
Route::get('departments/{department}', ShowDepartment::class)->name('admin.departments.show');
Route::get('cities/{city}', ShowCity::class)->name('admin.cities.show');
Route::get('users', UserComponent::class)->name('admin.users.index');
