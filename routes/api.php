<?php

use App\Http\Controllers\Api\BucApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\RabApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/rabs', [RabApiController::class, 'index']);
Route::post('/invoices', [InvoiceApiController::class, 'store']);
Route::post('/sync-bucs', [BucApiController::class, 'receive_buc_payreqs']);
