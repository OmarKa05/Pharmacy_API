<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MedicineController;

// Route::resource('medicines', MedicineController::class);

// Public Routes
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/medicines', [MedicineController::class, 'index']);
Route::get('/medicines/{id}', [MedicineController::class, 'show']);
Route::get('/medicines/search/{name}', [MedicineController::class, 'searchname']);
Route::get('/medicines/search/{company}', [MedicineController::class, 'searchcompany']);
Route::get('/medicines/search/{ScientificComposition}', [MedicineController::class, 'searchScientificComposition']);

Route::get('/companys', [CompanyController::class, 'index']);
Route::get('/companys/{id}', [CompanyController::class, 'show']);
Route::get('/companys/search/{name}', [CompanyController::class, 'searchname']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => ['admin']], function () {
        Route::post('/admin/create-user', [AuthController::class, 'createUser']);     // admin middleware for user creation
        Route::post('/medicines', [MedicineController::class, 'store']);
        Route::put('/medicines/{id}', [MedicineController::class, 'update']);
        Route::delete('/medicines/{id}', [MedicineController::class, 'destroy']);
        Route::put('/companys/{id}', [CompanyController::class, 'update']);
        Route::delete('/companys/{id}', [CompanyController::class, 'destroy']);
    });

    Route::middleware(['auth', 'user.cart'])->group(function () {
        Route::post('/cart', [CartController::class, 'addToCart']); // Updated to handle array
        Route::get('/cart', [CartController::class, 'viewCart']);
        Route::delete('/cart/{itemId}', [CartController::class, 'removeFromCart']);
    });    
    

});
