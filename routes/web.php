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
    Route::get('/reports/filter/project/name', 'resource_consumption_for_project_name')->middleware('auth');
    Route::get('/reports/filter/hostname/vmware', 'resource_consumption_for_hostname_or_vmware')->middleware('auth');
    Route::get('/reports/filter/btween/dates', 'resource_consumption_btween_dates')->middleware('auth');
    Route::get('/reports/{id}/{date_start}/{dante_end}', 'resource_consumption_grafic')->middleware('auth')->name('reports.grafic');
    Route::get('/reports/export/{date_start}/{dante_end}/{idserver}', 'generate_report_resource_history')->middleware('auth')->name('reports.grafic.export.resource_history');
    Route::get('/reports/it/tariff', 'resource_consumption_it_tariff')->middleware('auth')->name('reports.it_tariff');
    Route::get('/reports/it/tariff/project/{id}/{date_start}/{date_end}', 'resource_consumption_it_tariff_by_project')->middleware('auth')->name('reports.it_tariff_servers');
    Route::get('/reports/it/tariff/bwteen/dates', 'resource_consumption_it_tariff_btween_dates')->middleware('auth');
    Route::get('/reports/export/ittariff/{date_start}/{date_end}/{idproject}', 'generate_report_it_tariff')->middleware('auth')->name('generate.report.it_tariff');
    Route::get('/reports/server/summary', 'server_summary')->middleware('auth')->name('reports.server.summary');
    Route::get('/reports/filter/server/project/name', 'server_summary_for_proyect')->middleware('auth');
    Route::get('/reports/filter/server/hostname/vmware', 'server_summary_for_hostname_or_vmware')->middleware('auth');
    Route::post('/reports/server/summary/{id}', 'update_server_summary')->middleware('auth');
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
    Route::post('/maintenance/costs/import', 'import_CSV')->middleware('auth');
    Route::get('/maintenance/costs/filter/{date}', 'maintenance_cost_by_month')->middleware('auth');
    Route::post('/maintenance/costs/fourwalls/create', 'store_fourwall')->middleware('auth');
    Route::post('/maintenance/costs/nexus/create', 'store_nexus')->middleware('auth');
    Route::post('/maintenance/costs/hps/create', 'store_hp')->middleware('auth');
    Route::get('/maintenance/licence/spla', 'licence_spla')->middleware('auth')->name('maintenance.licence.spla');
    Route::post('/maintenance/licence/spla/create', 'store_licence_spla')->middleware('auth');
    Route::put('/maintenance/licence/spla/update/{id}', 'update_licence_spla')->middleware('auth');
    Route::put('/maintenance/licence/spla/update/status/{id}', 'update_status_licence_spla')->middleware('auth');
    Route::get('/maintenance/costs/fourwall/{id}', 'fourwall_details')->middleware('auth')->name('fourwall.details');
    Route::get('/maintenance/costs/nexus/{id}', 'nexus_details')->middleware('auth')->name('nexus.details');
    Route::get('/maintenance/costs/hp/{id}', 'hp_details')->middleware('auth')->name('hp.details');
    Route::put('/maintenance/costs/fourwall/update/{id}', 'update_fourwall')->middleware('auth');
    Route::put('/maintenance/costs/nexus/update/{id}', 'update_nexus')->middleware('auth');
    Route::put('/maintenance/costs/hp/update/{id}', 'update_hp')->middleware('auth');
    Route::put('/maintenance/costs/fourwall/update/status/{id}', 'update_fourwall_status')->middleware('auth');
    Route::put('/maintenance/costs/nexus/update/status/{id}', 'update_nexus_status')->middleware('auth');
    Route::put('/maintenance/costs/hp/update/status/{id}', 'update_hp_status')->middleware('auth');
    Route::get('/maintenance/licence/spla/servers', 'licence_spla_servers')->middleware('auth')->name('licence.spla.servers');
});
