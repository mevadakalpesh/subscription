<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PlanController;

use Illuminate\Http\Request;
//use Laravel\Cashier\Http\Controllers\WebhookController;
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

Route::get('/', function () {
  return view('welcome');
});


//Post Controller

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
  Route::get('subscription/checkout/{priceid}', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
  Route::post('subscription/create', [SubscriptionController::class, 'subscriptionCreate'])->name('subscription.create');
  //post controller route
  Route::resource('posts', PostController::class);

  //plan controller
  Route::controller(PlanController::class)->group(function() {
    Route::get('/plans', 'index')->name('plans');
    Route::get('/plan/{plan}', 'show')->name('plans.show');
    Route::get('/create/plan', 'createPlan')->name('create.plan');
    Route::post('/store/plan', 'storePlan')->name('store.plan');
    Route::post('/delete/plan', 'deletePlan')->name('delete.plan');
  });

});

Route::get('subscription/result', function(Request $request) {
  print_r($request->all());
})->name('subscription.result');


Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);


