<?php

use App\Http\Controllers\DatabaseTestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



use App\Http\Controllers\ProxyController;

Route::get('/', [ProxyController::class, 'index'])->name('proxy.index');
Route::match(['post'], '/', [ProxyController::class, 'parseAndShow']);
Route::get('/test-basic', function () {
    return 'Basic route works!';
});
Route::get('/test-db', [DatabaseTestController::class, 'testDatabaseConnection']);
