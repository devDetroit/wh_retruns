<?php

use App\Http\Controllers\PrintLabelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReturnApiController;
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


Route::get('returns', [ReturnApiController::class, 'index']);
Route::get('user', [ReturnApiController::class, 'loginUser']);
Route::get('tracking/{tracking}', [ReturnApiController::class, 'getTrackingNumberCount']);
Route::get('elpDashboard', [ReturnApiController::class, 'getELPProductionDashboard']);
Route::get('jrzDashboard', [ReturnApiController::class, 'getJRZProductionDashboard']);
Route::get('photos', [ReturnApiController::class, 'getPhotosPerPartNumber']);
Route::post('return/partnumbers', [ReturnApiController::class, 'submitPartNumber']);
Route::post('return', [ReturnApiController::class, 'submitTrackingNumber']);
