<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });



// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\RecipeController::class, 'index']);


Route::resource('/recipe' , RecipeController::class);
Route::get('/search' , [RecipeController::class , 'search']);
Route::get('/sort' , [RecipeController::class , 'sort']);

// Route::delete('/delete' , [RecipeController::class , 'destroy']);

// Route::get('/image' , function(){
//     return view('lara_ocr.upload_image');
// });


// Route::GET('/image2' , function(){
//     return view('lara_ocr.parsed_text');
// });


