<?php

use App\Http\Controllers\DispatchController;
use App\Http\Controllers\LinemanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Auth::routes(['register' => true]);

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');


Route::get('/about', function () {
    return view('about');
})->name('about');

Route::resource('/password', UserController::class)
    ->except(['show', 'edit', 'update'])
    ->name('index', 'password');

Route::resource('/dispatch', DispatchController::class)->only(['index'])
    ->name('index', 'dispatch');


Route::get('/incidents/{id}/dispatch', [DispatchController::class, '_dispatch'])
    ->name('incidents.dispatch');

Route::post('/incidents/{id}/dispatch', [DispatchController::class, 'store'])
    ->name('dispatch.store');

Route::resource('/incidents', IncidentsController::class)
    ->except(['add', 'info', 'update'])
    ->name('index', 'incidents')
    ->name('store', 'indicent.create');

Route::post('/incidents/{id}/add', [IncidentsController::class, 'add'])
    ->name('incidents.add');

Route::get('/incidents/{id}/info', [IncidentsController::class, 'info'])
    ->name('incidents.info');

Route::put('/incidents/{incidentId}/{infoId}', [IncidentsController::class, 'update'])
    ->name('incidents.update');


Route::resource('/lineman', LinemanController::class)
    ->except(['create', 'edit'])
    ->name('index', 'lineman');

Route::post('/lineman/{id}/reset', [LinemanController::class, 'reset'])
    ->name('lineman.reset');


Route::resource('/units', UnitsController::class)
    ->except(['show', 'edit', 'update'])
    ->name('index', 'units');

Route::get('/units/{id}/logs', [UnitsController::class, 'logs'])
    ->name('unit.logs');

Route::get('/units/{id}/refresh', [UnitsController::class, 'refresh'])
    ->name('unit.refresh');

