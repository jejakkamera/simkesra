<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\Bca as BcaController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/bca/v1.0/access-token/b2b', [BcaController::class, 'getAccessToken']);
// Route::post('/bca/v1.0/transfer-va/inquiry', [BcaController::class, 'inquiry']);


Route::fallback(function (Request $request) {
    return response()->json([
        'responseCode' => '404',
        'responseMessage' => 'Route not found.',
        'data' => []
    ], 404);
});
// Route::get('/bca/v1.0/access-token/b2b', [BcaController::class, 'getAccessToken']);
// Route::post('/bca/inquiry-transaction', [BcaController::class, 'inquiryTransaction']);
