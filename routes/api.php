<?php

use App\Http\Controllers\SortController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('sort/categories', [SortController::class, 'categories'])->name('api.sort.categories');
Route::post('sort/subcategories', [SortController::class, 'subcategories'])->name('api.sort.subcategories');
