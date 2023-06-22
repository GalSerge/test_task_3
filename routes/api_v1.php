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

Route::get('/notebook', [ApiController::class, 'get_notes']);
Route::post('/notebook', [ApiController::class, 'add_note']);

Route::get('/notebook/{id}', [ApiController::class, 'get_one_note']);

