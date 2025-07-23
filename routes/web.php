<?php

use Illuminate\Support\Facades\Route;
use App\Http\controllers\userController;
use App\Http\controllers\bigController;
use App\Http\Controllers\FilmController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user', [userController::class, "user"])->name('user');
Route::get('/index', [bigController::class, "index"])->name('home');



Route::get('films', [FilmController::class, 'index'])->name('films.index');
Route::post('films/store', [FilmController::class, 'store'])->name('films.store');
Route::post('films/update/{id}', [FilmController::class, 'update'])->name('films.update');
Route::delete('films/delete/{id}', [FilmController::class, 'destroy'])->name('films.delete');
Route::get('films/{id}', [FilmController::class, 'show'])->name('films.show');
