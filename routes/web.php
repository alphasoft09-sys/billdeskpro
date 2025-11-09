<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/debug', function () {
    return view('debug');
});

// User Management Routes
Route::resource('users', UserController::class);

// Inventory Management Routes
Route::get('/inventory', [InventoryController::class, 'index']);
Route::get('/inventory/products', [InventoryController::class, 'getProducts']);
Route::get('/inventory/products/{id}', [InventoryController::class, 'getProduct']);
Route::post('/inventory/products', [InventoryController::class, 'storeProduct']);
Route::post('/inventory/products/{id}', [InventoryController::class, 'updateProduct']);
Route::delete('/inventory/products/{id}', [InventoryController::class, 'deleteProduct']);

Route::get('/inventory/categories', [InventoryController::class, 'getCategories']);
Route::post('/inventory/categories', [InventoryController::class, 'storeCategory']);
Route::post('/inventory/categories/{id}', [InventoryController::class, 'updateCategory']);
Route::delete('/inventory/categories/{id}', [InventoryController::class, 'deleteCategory']);

Route::get('/inventory/units', [InventoryController::class, 'getUnits']);
Route::post('/inventory/units', [InventoryController::class, 'storeUnit']);
Route::post('/inventory/units/{id}', [InventoryController::class, 'updateUnit']);
Route::delete('/inventory/units/{id}', [InventoryController::class, 'deleteUnit']);

Route::get('/inventory/suppliers', [InventoryController::class, 'getSuppliers']);

Route::get('/inventory/products/{productId}/conversions', [InventoryController::class, 'getUnitConversions']);
Route::post('/inventory/conversions', [InventoryController::class, 'storeUnitConversion']);
Route::post('/inventory/conversions/{id}', [InventoryController::class, 'updateUnitConversion']);
Route::delete('/inventory/conversions/{id}', [InventoryController::class, 'deleteUnitConversion']);

// Batch Management Routes
Route::get('/inventory/batches', [BatchController::class, 'index']);
Route::get('/inventory/batches/data', [BatchController::class, 'getBatches']);
Route::get('/inventory/batches/{id}', [BatchController::class, 'show']);
Route::get('/inventory/products/{productId}/batches', [BatchController::class, 'getBatchesForProduct']);
Route::post('/inventory/batches', [BatchController::class, 'store']);
Route::post('/inventory/batches/{id}', [BatchController::class, 'update']);
Route::delete('/inventory/batches/{id}', [BatchController::class, 'destroy']);
Route::post('/inventory/batches/{id}/adjust-stock', [BatchController::class, 'adjustStock']);
Route::get('/inventory/batch-stats', [BatchController::class, 'getBatchStats']);

// Supplier Management Routes
Route::get('/suppliers', [SupplierController::class, 'index']);
Route::get('/suppliers/all', [SupplierController::class, 'getAll']);
Route::post('/suppliers', [SupplierController::class, 'store']);
Route::post('/suppliers/{id}', [SupplierController::class, 'update']);
Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy']);
