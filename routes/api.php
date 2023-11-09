<?php

use App\Http\Controllers\PrintLabelController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReturnApiController;
use App\Http\Controllers\UsersController;

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
Route::get('returns/report', [ReturnApiController::class, 'selectTrackingsReport']);
Route::get('returns/fedex', [ReturnApiController::class, 'selectFedexReport']);
Route::get('returns/tracking', [ReturnApiController::class, 'selectTrackingNumbers']);
Route::get('returns/order', [ReturnApiController::class, 'selectOrders']);
Route::get('returnsCondition', [ReturnApiController::class, 'returnsCondition']);
Route::get('user', [ReturnApiController::class, 'loginUser']);
Route::get('tracking/{tracking}', [ReturnApiController::class, 'getTrackingNumberCount']);
Route::get('elpDashboard', [ReturnApiController::class, 'getELPProductionDashboard']);
Route::get('jrzDashboard', [ReturnApiController::class, 'getJRZProductionDashboard']);
Route::get('photos', [ReturnApiController::class, 'getPhotosPerPartNumber']);
Route::post('return/partnumbers', [ReturnApiController::class, 'submitPartNumber']);
Route::post('return', [ReturnApiController::class, 'submitTrackingNumber']);

Route::get('/users/get', [App\Http\Controllers\UsersController::class, 'getUsers']);
Route::post('/users/store', [App\Http\Controllers\UsersController::class, 'store']);
Route::get('/users/delete/{id}', [App\Http\Controllers\UsersController::class, 'delete']);
Route::get('/folio/{folio}', [App\Http\Controllers\UsersController::class, 'showEmpleado']);
Route::post('/reestablecer/contrasena', [UsersController::class, 'reset']);

Route::post('/report/get/data', [ReportController::class, 'getData']);