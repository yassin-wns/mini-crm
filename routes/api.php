<?php

use App\Http\Controllers\Api\CustomerController as ApiCustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Mini CRM
|--------------------------------------------------------------------------
|
| Simple REST endpoints for customer management returning JSON responses.
|
*/

Route::get('/customers', [ApiCustomerController::class, 'index']);
Route::get('/customers/{id}', [ApiCustomerController::class, 'show']);
Route::post('/customers', [ApiCustomerController::class, 'store']);
Route::put('/customers/{id}', [ApiCustomerController::class, 'update']);
Route::patch('/customers/{id}', [ApiCustomerController::class, 'update']);
Route::delete('/customers/{id}', [ApiCustomerController::class, 'destroy']);
