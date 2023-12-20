<?php

use App\Http\Controllers\Post\PostController;
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
Route::get('/', [PostController::class,'index']);

Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class,'index'])->name('posts.index');
    Route::get('/data', [PostController::class, 'getPosts'])->name('posts.data');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/store', [PostController::class,'store'])->name('posts.store');
    Route::get('/{id}', [PostController::class,'show'])->name('posts.show');
    Route::get('/{id}/edit', [PostController::class,'edit'])->name('posts.edit');
    Route::put('/{id}', [PostController::class,'update'])->name('posts.update');
    Route::delete('/{id}', [PostController::class,'destroy'])->name('posts.destroy');

});
