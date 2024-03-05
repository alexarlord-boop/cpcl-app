<?php

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
Route::post('/parse-and-show', [ProxyController::class, 'parseAndShow'])->name('proxy.parseAndShow');
