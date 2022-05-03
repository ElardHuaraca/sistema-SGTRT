<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TChangeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

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

Route::controller(UserController::class)->group(function(){
    Route::get('/users', 'index')->middleware('auth')->name('users');
    Route::get('/users/{id}', 'show')->middleware('auth');
    Route::post('/users/create', 'store')->middleware('auth');
    Route::put('/users/update', 'update')->middleware('auth');
    Route::put('/users/update/status/{id}', 'update_status')->middleware('auth');
    Route::delete('/users/delete/{id}', 'destroy')->middleware('auth');
});

Route::controller(ProjectController::class)->group(function(){
    Route::get('/projects', 'index')->middleware('auth')->name('projects');
    Route::get('/projects/{id}', 'show')->middleware('auth');
    Route::post('/projects/create', 'store')->middleware('auth');
    Route::put('/projects/update/{id}', 'update')->middleware('auth');
    Route::delete('/projects/delete/{id}', 'destroy')->middleware('auth');
});
