<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LoginController;
use \App\Http\Controllers\HomeController;
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
Route::redirect("/","/welcome");
Route::view("/welcome","welcome.index")->name("welcome");
Route::get("/home", [HomeController::class, 'index'])->name("home");

Route::get('/login', [LoginController::class, 'redirectToProvider'])->name("login");
Route::get('/add-bot', [LoginController::class, 'handleProviderCallback'])->name("add-bot");

//Route::redirect("/login-callback","/home");
Route::get('/login-callback', [LoginController::class, 'loginCallback']);

Route::get('/discord/bot-added', [LoginController::class, 'handleBotCallback']);


//Route::view("/login","welcome")->name("login");
Route::view("/register","welcome")->name("register");

//Route::get('/yo', function () {
//    return view('welcome');
//});
