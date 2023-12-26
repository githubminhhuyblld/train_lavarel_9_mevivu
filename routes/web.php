<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Category\CategoryController;
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
/** Auth */
Route::group(['middleware' => ['guest']], function () {

    /**
     * Register Routes
     */
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

    /**
     * Login Routes
     */
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

    /**
     * Logout Route
     */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


/**
 * Home Route
 */
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/', [PostController::class, 'index'])->name('posts.index')->middleware('auth');

/**
 * Categories Route
 */
Route::group(['prefix' => 'categories', 'middleware' => 'auth'], function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/data', [CategoryController::class, 'getData'])->name('categories.data');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('posts.edit');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('categories.destroy');
    Route::post('/mass-delete', [CategoryController::class, 'massDelete'])->name('categories.massDelete');


});

/**
 * Posts Route
 */
Route::group(['prefix' => 'posts', 'middleware' => 'auth'], function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/data', [PostController::class, 'getPosts'])->name('posts.data');
    Route::get('/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/store', [PostController::class, 'store'])->name('posts.store');
    Route::get('/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/mass-delete', [PostController::class, 'massDelete'])->name('posts.massDelete');
});

