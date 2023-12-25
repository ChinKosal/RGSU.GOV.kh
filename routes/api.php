<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api as Api;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('authorize', [Api\UserDeviceController::class, 'authorization']);

Route::controller(Api\NewsController::class)->group(function() {
    //Route::post('home', 'listActiveNews');
    Route::post('detail', 'viewNews');
    Route::post('home', 'homeScreen');
    
    Route::post('decryptPhp', 'decryptPhp');
    Route::post('encryptPhp', 'encryptPhp');
    
});

Route::controller(Api\CategoriesController::class)->group(function() {
    Route::post('categories', 'listActiveCategories');
    Route::post('news-category', 'listNewsByCategoryId');
});

