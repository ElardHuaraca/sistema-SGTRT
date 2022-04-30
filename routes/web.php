<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TChangeController;
use App\Http\Controllers\ReportController;

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

Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::get('/', function () {

    if(Auth::user()->rol == null){
        Auth::logout();
        abort(403,'No tiene permisos para acceder a esta página');
    }

    $tChange = TChangeController::getTChange();
    Debugbar::info($tChange);
    return view('home',['tChange' => $tChange]);
})->middleware('auth')->name('home');

Route::controller(TChangeController::class)->group(function(){
    Route::put('/update/tchange', 'updateTChange')->middleware('auth');
});

Route::controller(ReportController::class)->group(function(){
    Route::get('/reports', 'index')->middleware('auth')->name('reports');
    Route::get('/reports/{id}', 'show')->middleware('auth')->name('reports.grafic');
    Route::post('/reports/create', 'store')->middleware('auth');
    Route::put('/reports/update', 'update')->middleware('auth');
    Route::delete('/reports/delete', 'destroy')->middleware('auth');
});
