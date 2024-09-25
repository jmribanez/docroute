<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ChatController;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes(['register' => false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('/user', UserController::class);
Route::resource('/document', DocumentController::class);
Route::resource('/office', OfficeController::class);
Route::resource('/template', TemplateController::class);
Route::get('/attachment/{url}/download', [AttachmentController::class, 'download'])->name('attachment.download');
Route::post('/attachment/{url}/delete', [AttachmentController::class, 'delete'])->name('attachment.delete');
Route::get('/receive/{id}', [DocumentRouteController::class, 'receive'])->name('documentroute.receive');
Route::post('/receive/{id}', [DocumentRouteController::class, 'confirm'])->name('documentroute.confirm');
Route::post('/prepare/{id}', [DocumentRouteController::class, 'prepare'])->name('documentroute.prepare');
Route::post('/addRecepients', [DocumentRouteController::class, 'addRecepients'])->name('documentroute.addRecepients');
Route::post('/sendDocument/{id}', [DocumentRouteController::class, 'sendDocument'])->name('documentroute.sendDocument');
Route::post('/approveDocument', [DocumentRouteController::class, 'approveDocument'])->name('documentroute.approveDocument');
Route::post('/resetRoute/{id}', [DocumentRouteController::class, 'resetRoute'])->name('documentRoute.resetRoute');
Route::get('/findUser/{searchname}',[UserController::class, 'ajax_findUser'])->name('user.find');
Route::get('/qr/{id}', [DocumentController::class, 'printQR'])->name('document.printqr');
Route::post('/setAction/{id}', [DocumentRouteController::class, 'setAction'])->name('documentroute.setaction');
Route::post('/finishRoute/{id}', [DocumentRouteController::class, 'finishRoute'])->name('documentroute.finishroute');
Route::post('/chat', [ChatController::class, 'chat'])->name('chat'); // access using AJAX
