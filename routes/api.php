<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MovieController;
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

Route::put('/movies/{movie}/likes', [MovieController::class, 'movieLikeStatus'])->middleware('auth');
Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])->middleware('auth');
Route::delete('/movies/{movie}/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth');

Route::apiResource('/movies', MovieController::class)->middleware('auth');

Route::get('/get-configuration', [MovieController::class, 'getConfiguration'])->middleware('auth');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/me', [AuthController::class, 'me'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
