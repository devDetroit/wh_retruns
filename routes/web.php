<?php

use App\Http\Controllers\ReturnController;
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

Route::get('/', function () {
    return view('bootstrap.layouts.layout');
})->middleware('auth');


Route::controller(ReturnController::class)->group(function () {
    Route::get('/returns', 'index');
    Route::get('/returns/create', 'create');
    Route::post('/returns/store', 'store');
    Route::post('/returns/files', 'storeFiles');
});

/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
 */
require __DIR__ . '/auth.php';
