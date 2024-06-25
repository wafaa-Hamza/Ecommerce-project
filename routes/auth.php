<?php

use App\Http\Controllers\API\V1\Auth\AuthController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::middleware(['auth:api', 'role:admin,user'])->group(function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
});
// Route::post("/check-otp", [AuthController::class, "check_otp"])->middleware("loggedIn");
Route::get("/logout", [AuthController::class, "logout"])->middleware("loggedIn");
// Route::get("/logout-all", [AuthController::class, "logoutAllDevices"])->middleware("loggedIn");

Route::get("/forget-password", [AuthController::class, "forget_password"]);
Route::post("/check-forget-password", [AuthController::class, "check_forget_password_otp"]);
Route::post("/reset-password", [AuthController::class, "reset_password"])->middleware("loggedIn");
