<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCreateController;
use App\Http\Controllers\ProductEditController;

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

Route::get('/', [AuthController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('showRegistrationForm');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
//Route::get('home',[HomeController::class,'home']) -> name('home');

Route::get('home', function () {
    return view('home');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/create', [ProductCreateController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductCreateController::class, 'store'])->name('products.store');

Route::get('/products/{id}', [ProductController::class, 'showDetail'])->name('products.showDetail');
Route::delete('/products/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');

Route::get('/products/edit/{id}', [ProductEditController::class, 'edit'])->name('products.edit');
Route::match(['put', 'post'], 'products/edit/{id}', [ProductEditController::class, 'update'])->name('products.update');
