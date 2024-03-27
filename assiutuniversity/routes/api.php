<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

// JWT-protected routes
// Route::group(['middleware'=>'api','prefix'=>'auth'],function($router){
//     Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
//     Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
//     Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
// });



    // Route::post('/registerweb', [AuthController::class, 'registerWeb'])->name('register');
    // Route::post('/loginweb', [AuthController::class, 'loginWeb'])->name('login');
