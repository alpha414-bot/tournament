<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Models\Link;

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

Route::get('/', function(){
    return view('dashboard', ['tournaments'=>Link::latest()->get()]); 
});
Route::get('/dashboard', function () {
    return view('dashboard', ['tournaments'=>Link::latest()->get()]);
})->middleware(['auth'])->name('dashboard');

Route::get('/app/view/{id}', function($id){
    return view('view', ['id'=>$id]);
})->name('iframe.view');

Route::get('/admin/dashboard', [MainController::class, 'index'])->middleware(['auth', 'masterauth'])->name('admin-dashboard');

Route::post('/admin/dashboard/create/link', [MainController::class, 'store'])->middleware(['auth', 'masterauth'])->name('add tournament');
Route::get('/link/{id}/delete/{tournament_id}', [MainController::class, 'delete'])->middleware(['auth', 'masterauth']);

Route::get('/setting', [MainController::class, 'setting'])->middleware(['auth', 'masterauth'])->name('settings');
Route::post('/setting/password-reset', [MainController::class, 'resetPassword'])->middleware(['auth', 'masterauth'])->name('settings.passwordreset');
Route::post('/setting/change-sitename', [MainController::class, 'sitename'])->middleware(['auth', 'masterauth'])->name('sitename');

require __DIR__.'/auth.php';
