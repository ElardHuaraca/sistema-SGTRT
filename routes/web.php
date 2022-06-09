<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaintenanceController;

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

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->middleware('auth')->name('home');
    Route::put('/update/tchange', 'updateTChange')->middleware('auth');
});

Route::controller(ReportController::class)->group(function () {
    Route::get('/reports', 'resource_consumption')->middleware('auth')->name('reports');
    Route::get('/reports/{id}', 'resource_consumption_grafic')->middleware('auth')->name('reports.grafic');
    Route::post('/reports/create', 'store')->middleware('auth');
    Route::put('/reports/update', 'update')->middleware('auth');
    Route::delete('/reports/delete', 'destroy')->middleware('auth');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->middleware('auth')->name('users');
    Route::get('/users/{id}', 'show')->middleware('auth');
    Route::post('/users/create', 'store')->middleware('auth');
    Route::put('/users/update', 'update')->middleware('auth');
    Route::put('/users/update/state/{id}', 'update_status')->middleware('auth');
    Route::delete('/users/delete/{id}', 'destroy')->middleware('auth');
});

Route::controller(MaintenanceController::class)->group(function () {
    Route::get('/maintenance/sows', 'sow')->middleware('auth')->name('maintenance.sow');
    Route::post('/maintenance/sows/create', 'store_sow')->middleware('auth');
    Route::put('/maintenance/sows/update/{id}', 'update_sow')->middleware('auth');
    Route::put('/maintenance/sows/update/status/{id}', 'update_sow_status')->middleware('auth');
    Route::get('/maintenance/projects', 'project')->middleware('auth')->name('maintenance.projects');
    Route::post('/maintenance/projects/create', 'store_project')->middleware('auth');
    Route::put('/maintenance/projects/update/{id}', 'update_project')->middleware('auth');
    Route::put('/maintenance/projects/update/status/{id}', 'update_project_status')->middleware('auth');
    Route::get('/maintenance/costs', 'maintenance_cost')->middleware('auth')->name('maintenance.costs');
    Route::post('/maintenance/costs/fourwalls/create', 'store_fourwall')->middleware('auth');
    Route::post('/maintenance/costs/nexus/create', 'store_nexus')->middleware('auth');
    Route::post('/maintenance/costs/hps/create', 'store_hp')->middleware('auth');
    Route::get('/maintenance/licence/spla', 'licence_spla')->middleware('auth')->name('maintenance.licence.spla');
    Route::post('/maintenance/licence/spla/create', 'store_licence_spla')->middleware('auth');
    Route::put('/maintenance/licence/spla/update/{id}', 'update_licence_spla')->middleware('auth');
    Route::put('/maintenance/licence/spla/update/status/{id}', 'update_status_licence_spla')->middleware('auth');
});
