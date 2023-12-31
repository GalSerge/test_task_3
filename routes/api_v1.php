<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\ApiController;
/*
|--------------------------------------------------------------------------
| API Routes V1
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [ApiController::class, 'index']);

Route::get('/notebook', [ApiController::class, 'getNotes']);
Route::post('/notebook', [ApiController::class, 'addNote']);

Route::get('/notebook/{id}', [ApiController::class, 'getOneNote']);
Route::post('/notebook/{id}', [ApiController::class, 'editNote']);

Route::delete('/notebook/{id}', [ApiController::class, 'deleteNote']);

