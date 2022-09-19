<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api','app']],function(){
    Route::group(['prefix' => 'access'], function(){
        Route::group(['prefix' => 'post'], function(){
            Route::get('',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'index'])->name('api.v1.access.post.index');
            Route::get('create',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'create'])->name('api.v1.access.post.create');
            Route::get('{id}/edit',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'edit'])->name('api.v1.access.post.edit');
            Route::get('{id}/show',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'show'])->name('api.v1.access.post.show');
            Route::post('store',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'store'])->name('api.v1.access.post.store');
            Route::put('{id}/update',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'update'])->name('api.v1.access.post.update');
            Route::delete('{id}/delete',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'destroy'])->name('api.v1.access.post.delete');
            Route::delete('delete_selected',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'destroy_selected'])->name('api.v1.access.post.delete.selected');
            Route::get('{id}/file',[Modules\Post\Http\Controllers\Api\V1\PostController::class, 'file'])->name('api.v1.access.post.file');
        });
    });
});