<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginController;
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
Route::get('/a', [LoginController::class, 'redirectToProvider']);

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
<<<<<<< HEAD

Route::get('/discord/bot-added', [LoginController::class, 'handleBotCallback']);
=======
>>>>>>> f_dev

Route::get('/yo', function () {
    return view('welcome');
});
