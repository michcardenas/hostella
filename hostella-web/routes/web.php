<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertiesController;



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
Route::get('/', [HomeController::class, 'index'])->name('home');

// Páginas estáticas
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/for-owners', [HomeController::class, 'forOwners'])->name('for-owners');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Propiedades

Route::get('/propiedades', [PropertiesController::class, 'index'])->name('properties.index');
Route::get('/propiedades/{id}', [PropertiesController::class, 'show'])->name('properties.show');
Route::get('/propiedades/{id}/reservar', [PropertiesController::class, 'showReservationForm'])->name('properties.reservation');
Route::post('/propiedades/{id}/reservar', [PropertiesController::class, 'createReservation'])->name('properties.reserve');


// Reservas
Route::get('/book/{propertyId}', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/book/{propertyId}', [ReservationController::class, 'store'])->name('reservations.store');

// Ruta de prueba para Guesty API
// Rutas de prueba para Guesty API
Route::prefix('guesty-test')->group(function () {
    Route::get('/base-urls', [App\Http\Controllers\GuestyTestController::class, 'testBaseUrl']);
    Route::get('/auth', [App\Http\Controllers\GuestyTestController::class, 'testAuthUrl']);
    Route::get('/token', [App\Http\Controllers\GuestyTestController::class, 'validateToken']);
});

// En routes/web.php
Route::prefix('token-test')->group(function () {
    Route::get('/status', [App\Http\Controllers\TokenTestController::class, 'testTokenSystem']);
    Route::get('/refresh', [App\Http\Controllers\TokenTestController::class, 'manualRefreshToken']);
});