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
        Route::group(['prefix' => 'category'], function(){
            Route::get('',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'index'])->name('api.v1.access.category.index');
            Route::get('create',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'create'])->name('api.v1.access.category.create');
            Route::get('{id}/edit',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'edit'])->name('api.v1.access.category.edit');
            Route::post('store',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'store'])->name('api.v1.access.category.store');
            Route::put('{id}/update',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'update'])->name('api.v1.access.category.update');
            Route::delete('{id}/delete',[Modules\Category\Http\Controllers\Api\V1\CategoryController::class, 'delete'])->name('api.v1.access.category.delete');
            Route::delete('delete_selected',[Module\Category\Http\Controller\Api\V1\CategoryController::class, 'destroy_selected'])->name('api.v1.access.category.delete.selected');
        });
    });
});