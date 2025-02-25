<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Backend\Admin\CategoryController;
use App\Http\Controllers\Backend\Admin\PostController;
use App\Http\Controllers\Backend\Admin\TagController;
use App\Http\Controllers\Backend\Admin\UserController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProfilController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
    Route::post('/auth/login', [AuthController::class, 'prosesLogin'])->name('prosesLogin');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/upload',  'upload')->name('upload');
            Route::get('/datatable', 'datatable')->name('datatable');
        });
    });

    Route::controller(CategoryController::class)->prefix('categories')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');
    });

    Route::controller(TagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/datatable', 'datatable')->name('datatable');
        Route::get('/search', 'search')->name('search');
    });

    Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/tambah', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/upload',  'upload')->name('upload');
        Route::get('/datatable', 'datatable')->name('datatable');
        Route::post('/toggle-publish', 'togglePublish')->name('toggle-publish');
    });

    Route::controller(ProfilController::class)->prefix('profil')->name('profil.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update/{id}', 'update')->name('update');
    });
});

//Frontend controller

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/{slug}', [FrontendPostController::class, 'index'])->name('detailPost');
Route::get('/category/{slug}', [FrontendPostController::class, 'postCategory'])->name('postCategory');
