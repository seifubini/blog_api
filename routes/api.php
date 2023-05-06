<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('categories', CategoryController::class);
Route::resource('posts', PostController::class);

/**
 * Comments Routes
 */
Route::group(['prefix' => 'comments'], function() {
    Route::get('/{id}', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/store/{id}', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/show/{id}', [CommentController::class, 'show'])->name('comments.show');
    Route::patch('/update/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/delete/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
