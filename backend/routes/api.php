<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('tasks/{task}/pay', [PaymentController::class, 'processPayment']);
Route::get('tasks/{task}/pay/success', [PaymentController::class, 'paymentSuccess']);
Route::get('tasks/{task}/pay/cancel', [PaymentController::class, 'paymentCancel']);
Route::apiResource('tasks', TaskController::class);
Route::post('tasks/store', [TaskController::class,'store']);
Route::get('tasks', [TaskController::class,'index']);