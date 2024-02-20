<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


//registration,login,logout links
Route::get('/', [RegistrationController::class, 'loginview']);
Route::view('/registration', 'registration');
Route::POST('/reg', [RegistrationController::class, 'registration']);
Route::get('/viewReg', [RegistrationController::class, 'getData']);
Route::delete('/delete_user/{id}', [RegistrationController::class, 'delete_user']);
Route::POST('/custom-login-code', [RegistrationController::class, 'login']);
Route::get('/dashboard', [RegistrationController::class, 'dashboard']);
Route::get('/logout', [RegistrationController::class, 'logout']);

//categories links
Route::POST('/insert', [CategorieController::class, 'insert']);
Route::get('/categories', [CategorieController::class, 'getData']);
Route::get('/edit/{id}', [CategorieController::class, 'editForm']); // Display the edit form
Route::post('/edit/{id}', [CategorieController::class, 'update']); // Handle the form submission
Route::delete('/delete/{id}',[CategorieController::class,'delete']);


//products links
Route::get('/products', [ProductController::class, 'get_product_data']);
Route::POST('/insert_product', [ProductController::class, 'insert']);
Route::get('/view_product/{id}', [ProductController::class, 'view_product_data']);
Route::get('/edit_product/{id}', [ProductController::class, 'editForm']); // Display the edit form
Route::post('/edit_product/{id}', [ProductController::class, 'update']);// Handle the form submission
Route::delete('/delete-multiple', [ProductController::class, 'deleteMultiple']);





Route::get('/sendMail', [MailController::class, 'sendMail']);



