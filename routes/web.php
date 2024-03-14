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

Route::post('/process-saml', [ProxyController::class, 'processSamlEntity'])->name('process.saml');
Route::post('/edit-saml', [ProxyController::class, 'editSamlEntity'])->name('edit.saml');
Route::post('/process-oidc', [ProxyController::class, 'processOidcEntity'])->name('process.oidc');
Route::get('/clear-cache', [ProxyController::class, 'clearCache'])->name('clear.cache');
Route::get('/check-all', [ProxyController::class, 'checkAll'])->name('check.all');
Route::delete('/delete-entry/{protocol}/{type}/{id}', [ProxyController::class,'deleteEntry'])->name('deleteEntry');


