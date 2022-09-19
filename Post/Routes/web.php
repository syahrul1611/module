<?php

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

Route::group(['prefix' => 'admin', 'middleware' => ['auth','app']],function(){
    Route::group(['prefix' => 'v1'],function(){
        Route::group(['prefix' => 'access'], function(){
            Route::group(['prefix' => 'post'], function(){
                Route::get('',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'index'])->name('admin.v1.access.post.index');
                Route::get('create',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'create'])->name('admin.v1.access.post.create');
                Route::get('{id}/edit',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'edit'])->name('admin.v1.access.post.edit');
                Route::get('{id}/show',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'show'])->name('admin.v1.access.post.show');
                Route::post('store',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'store'])->name('admin.v1.access.post.store');
                Route::put('{id}/update',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'update'])->name('admin.v1.access.post.update');
                Route::get('{id}/delete',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'destroy'])->name('admin.v1.access.post.delete');
                Route::post('delete_selected',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'destroy_selected'])->name('admin.v1.access.post.delete.selected');
                Route::get('{id}/file',[Modules\Post\Http\Controllers\Web\V1\PostController::class, 'file'])->name('admin.v1.access.post.file');
            });
        });
    });
});
