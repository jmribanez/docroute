<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentRouteController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Models\DocumentRoute;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/user', UserController::class);
Route::resource('/document', DocumentController::class);
Route::resource('/office', OfficeController::class);
Route::resource('/template', TemplateController::class);
Route::get('/attachment/{url}/download', [AttachmentController::class, 'download'])->name('attachment.download');
Route::post('/attachment/{url}/delete', [AttachmentController::class, 'delete'])->name('attachment.delete');
Route::get('/receive/{id}', [DocumentRouteController::class, 'receive'])->name('documentroute.receive');
Route::post('/receive/{id}', [DocumentRouteController::class, 'confirm'])->name('documentroute.confirm');
Route::post('/send/{id}', [DocumentRouteController::class, 'send'])->name('documentroute.send');
Route::post('/sendToRecepients', [DocumentRouteController::class, 'sendToRecepients'])->name('documentroute.sendToRecepient');
Route::get('/findUser/{searchname}',[UserController::class, 'ajax_findUser'])->name('user.find');
