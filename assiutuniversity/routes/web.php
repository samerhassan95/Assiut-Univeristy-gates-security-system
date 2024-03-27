<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StaffVisitController;
use App\Http\Controllers\StudentVisitController;

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



// Dashboard (requires authentication)
Route::get('/assiutuniversity', function () {
    return view('welcome');
});

// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginform');
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('registerform'); 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// web.php

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('loginform');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registerform');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');




Route::post('/logout', [LoginController::class, 'logout'])->name('logout');




Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('staff', StaffController::class);
    // Route::get('/staff', [StaffController)
    // Route::resource('staff', StaffController::class)->except(['getAllStaff', 'store']);

    
    Route::resource('student_visits', StudentVisitController::class)->except(['store']);
    
    
    
    Route::resource('faculties', FacultyController::class);
    Route::resource('gates', GateController::class);
    Route::resource('users', UserController::class);
    Route::get('/staffvisits/history/{nationalID}', [StaffVisitController::class, 'show'])->name('staffvisits.history');
    Route::get('/studentvisits/history/{nationalID}', [StudentVisitController::class, 'show'])->name('studentvisits.history');
    
    Route::patch('/admin/toggle/{user}', [UserController::class, 'toggleRole'])->name('admin.toggle');
    Route::patch('/admin/approve/{user}', [UserController::class, 'approve'])->name('admin.approve');
    Route::patch('/admin/freeze/{user}', [UserController::class, 'freeze'])->name('admin.freeze');
    
    
    Route::get('/stats', [GateController::class, 'dashboard'])->name('stats');
    Route::get('/gate/details/{gateId}', [GateController::class, 'gateDetails'])
    ->name('gate.details');

    Route::post('/admin/approve/{user}', 'UserController@approve')->name('admin.approve');
    Route::post('/admin/freeze/{user}', 'UserController@freeze')->name('admin.freeze');
});
Auth::routes();

// Route::resource('staff', StaffController::class);

Route::resource('students', StudentController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('staff_visits', StaffVisitController::class);

Route::get('/allstaffvisits', [StaffVisitController::class, 'getAllVisits']);
Route::get('/allstudentvisits', [StudentVisitController::class, 'getStudentVisits']);
// routes/web.php or routes file
Route::get('/update-gate-details/{gateId}', [GateController::class, 'updateGateDetails'])->name('update.gate.details');

///////////////////
Route::get('/v1/allstaff', [StaffController::class, 'getAllStaff']);
Route::post('/v1/studentvisits', [StudentVisitController::class,'store']);
Route::post('/v1/staffvisits', [StaffVisitController::class,'store']);
Route::get('/v1/allgates', [GateController::class,'getAllGates']);
Route::get('/v1/students/serial/{serialNumber}', [StudentController::class,'getBySerial']);
Route::put('/v1/students/updateSerial/{nationalID}',  [StudentController::class,'updateSerial']);

