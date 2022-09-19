<?php

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
    Route::group(['prefix' => 'v1'], function(){
        Route::group(['prefix' => 'access'], function(){
            Route::group(['prefix' => 'category'], function(){
                Route::get('',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'index'])->name('admin.v1.access.category.index');
                Route::get('create',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'create'])->name('admin.v1.access.category.create');
                Route::get('{id}/edit',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'edit'])->name('admin.v1.access.category.edit');
                Route::post('store',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'store'])->name('admin.v1.access.category.store');
                Route::put('{id}/update',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'update'])->name('admin.v1.access.category.update');
                Route::get('{id}/delete',[Modules\Category\Http\Controllers\Web\V1\CategoryController::class, 'delete'])->name('admin.v1.access.category.delete');
                Route::post('delete_selected',[Module\Category\Http\Controller\Web\V1\CategoryController::class, 'destroy_selected'])->name('admin.v1.access.category.delete.selected');
            });
        });
    });
});
