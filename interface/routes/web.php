<?php

use App\Http\Controllers\LeadCollectorController;
use App\Http\Controllers\LeadCollectorFlow;
use App\Http\Controllers\LeadCollectorMessages;
use App\Http\Controllers\LeadCollectorTrackersController;
use App\Http\Controllers\LeadsStatisticsAjax;
use App\Http\Controllers\PartnerProviderController;
use App\Http\Controllers\StatusSchemeController;
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
  return view('app.main');
})->name('home');

Route::get('/leads', [LeadCollectorFlow::class, 'index'])
  ->name('leads')->middleware('auth');

Route::get('/scheduled-leads', [LeadCollectorFlow::class, 'scheduledLeads'])
  ->name('scheduled-leads')->middleware('auth');

Route::get('/leads/{clickid?}', [LeadCollectorFlow::class, 'show'])
  ->middleware('auth')->name('lead');

Route::get('/message', [LeadCollectorMessages::class, 'index'])
  ->name('message')->middleware('auth');

Route::get('/message/{id}', [LeadCollectorMessages::class, 'show'])->middleware('auth');

Route::get('/reorder-edited', [LeadCollectorController::class, 'reorderEdited'])
  ->name('reorderEdited')->middleware('auth');

Route::get('/refresh/{click_id}', [LeadCollectorController::class, 'refresh']);

Route::get('/delete/{unique_id}', [LeadCollectorController::class, 'delete']);

Route::get('/reorder/{unique_id}', [LeadCollectorController::class, 'reorder']);

Route::get('/download', [LeadCollectorFlow::class, 'downloadLead'])
  ->middleware('auth');

Route::get('/settings', [LeadCollectorController::class, 'leadCollectorSettings'])
  ->name('settings')->middleware('auth');

Route::get('/settings/status-scheme', [StatusSchemeController::class, 'addAd2LynxStatus'])
  ->name('statusScheme')->middleware('auth');

Route::post('/settings/status-scheme/related-status/add', [StatusSchemeController::class, 'loadScheme'])
  ->name('statusSchemeAdd')->middleware('auth');

Route::get('/settings/status-scheme/related-status/edit', [StatusSchemeController::class, 'editScheme'])
  ->name('statusSchemeEdit')->middleware('auth');

Route::get('/settings/status-scheme/status-category/new', [StatusSchemeController::class, 'addAd2LynxStatus'])
  ->name('add-new-ad2lynx-status-category')->middleware('auth');
//
Route::get('/settings/status-scheme/advertiser-statuses/{name}', [StatusSchemeController::class, 'advertiserStatusScheme'])
  ->name('advertiser-statuses-board')->middleware('auth');

//Route::get('/settings/status-scheme/status-category/new', [StatusSchemeController::class, 'addAd2LynxStatus'])
//    ->name('assign-advertiser-status-board')->middleware('auth');
//
Route::post('/settings/status-scheme/status-category/commit', [StatusSchemeController::class, 'commitAd2LynxStatus'])
  ->name('commit-new-ad2lynx-status-category')->middleware('auth');

Route::delete('/settings/status-scheme/status-category/delete/{id}', [StatusSchemeController::class, 'deleteAd2LynxStatus'])
  ->name('delete-ad2lynx-status-category')->middleware('auth');

Route::delete('/settings/status-scheme/related-status/delete/{id}', [StatusSchemeController::class, 'deleteRelatedStatus'])
  ->name('delete-related-status')->middleware('auth');

Route::resource('/settings/partner-providers', PartnerProviderController::class)
  ->except(['destroy'])->middleware('auth');

Route::resource('/settings/trackers', LeadCollectorTrackersController::class)
  ->except(['destroy'])->middleware('auth');

Route::get('/create-backfix', [LeadCollectorController::class, 'backfixConfigurator'])
  ->name('backfixConfigurator');

Route::get('/dashboard', function () {
  return redirect()->route('leads');
});


Route::post('/mass-assign', [LeadCollectorController::class, 'massAssign'])
  ->name('massAssign');


Route::get('/stat-ajax', [
  LeadsStatisticsAjax::class, 'index'
])->name('statistics');

Route::post('/stat-ajax', [
  LeadsStatisticsAjax::class, 'findByFilter'
]);

Route::post('/update-leads-payload', [LeadCollectorController::class, 'leadsDataFix'])
  ->name("update-leads-payload");

/**
 * Click gate documentation for everybody!
 */
Route::get('/clickgate', function () {
  return view("click-gate.doc");
});
