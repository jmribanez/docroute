<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
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
