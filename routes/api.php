<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ItemController;

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

Route::group(["prefix"=> "v1"], function() {
    /// Categories
    Route::group(["prefix" => "categories"], function() {
        Route::get("/", [CategoryController::class, 'index']);
        Route::get("{id}", [CategoryController::class, 'show']);
        Route::post("/", [CategoryController::class, 'store']);
        Route::patch("{id}", [CategoryController::class, 'update']);
        Route::delete("{id}", [CategoryController::class, 'destroy']);
    });

    /// Articles
    Route::group(["prefix" => "articles"], function() {
        Route::get("/", [ArticleController::class, 'index']);
        Route::get("{id}", [ArticleController::class, 'show']);
        Route::post("/", [ArticleController::class, 'store']);
        Route::patch("{id}", [ArticleController::class, 'update']);
        Route::delete("{id}", [ArticleController::class, 'destroy']);
    });

    /// Items
    Route::group(["prefix" => "items"], function() {
        Route::get("/", [ItemController::class, 'index']);
        Route::get("{id}", [ItemController::class, 'show']);
        Route::post("/", [ItemController::class, 'store']);
        Route::patch("{id}", [ItemController::class, 'update']);
        Route::delete("{id}", [ItemController::class, 'destroy']);
    });
});
