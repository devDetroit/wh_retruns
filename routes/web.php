<?php

use App\Http\Controllers\CountersController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\PrintLabelController;
use App\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {

    Route::controller(ReturnController::class)->group(function () {
        Route::get('returns', 'index');
        Route::get('/', 'index');
        Route::get('returns/create', 'create');
        Route::get('returns/{return}', 'show');
        Route::post('returns/store', 'store');
        Route::post('returns/files', 'storeFiles');
        Route::put('returns/{return}', 'update');
        Route::post('returns/{return}', 'destroy');
    });

    Route::get('labels/print', [PrintLabelController::class, 'index'])->name('labels');
    Route::get('labels/add', [PrintLabelController::class, 'create'])->name('upcnumber');
    Route::get('labels/counters', [CountersController::class, 'index']);
    Route::get('labels/actual', [CountersController::class, 'counters']);

    Route::get('target', [TargetController::class, 'index']);
    Route::get('target/create', [TargetController::class, 'create']);
    Route::post('target/create', [TargetController::class, 'store']);

    Route::get('returns/reports/general', function () {
        return view('returns.report-tracking');
    });
    Route::get('/elp-dashboard', function () {
        return view('returns.wh_dashboard');
    });
});

/* LABELS REMAN */
Route::get('upc', [PrintLabelController::class, 'validateUPC']);
Route::get('print', [PrintLabelController::class, 'printLabel']);
/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
 */
require __DIR__ . '/auth.php';
