<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CitasDisponiblesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HistorialCitasController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\PrediccionController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PersonalityTestController;


// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación
Auth::routes();

// Ruta para el registro de usuarios
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Ruta a la página principal después de iniciar sesión
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Ruta para cargar el contenido dinámico del panel
Route::get('/home/panel-content', [HomeController::class, 'panelContent'])->name('home.panelContent');

// Ruta para obtener las citas disponibles
Route::get('/citas', [CitasDisponiblesController::class, 'index'])->name('citas.index');

// Ruta para cargar las citas
Route::get('/appointments/content', [AppointmentController::class, 'getAppointmentsContent'])->name('appointments.content');

// Rutas relacionadas con el Test
Route::get('/admin/tests', [TestController::class, 'show'])->name('tests.show');
Route::post('/admin/tests', [TestController::class, 'submit'])->name('tests.submit');

// Agrupar rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas para las citas
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // Ruta para completar el test y agendar una cita
    Route::post('/complete-test', [HomeController::class, 'completeTest'])->name('complete.test');

    // Rutas para citas disponibles
    Route::get('/admin/citas_disponibles', [CitasDisponiblesController::class, 'index'])->name('citas_disponibles.index');

    Route::get('/admin/history', [HistorialCitasController::class, 'index'])->name('historial.citas.index');

    Route::get('/admin/datos', [StatisticsController::class, 'index'])->name('statistics.index');

    // Ruta para mostrar la vista de predicción y procesar la predicción
    Route::match(['get', 'post'], '/admin/predecir', [PrediccionController::class, 'index'])->name('predecir.index');

    //Route::get('/predecir', [PrediccionController::class, 'index'])->name('predecir.index');
    Route::post('/predecir/procesar', [PrediccionController::class, 'procesar'])->name('predecir.procesar');

    Route::get('/admin/RCita', [CitaController::class, 'create'])->name('citas.create');
    Route::post('/admin/RCita', [CitaController::class, 'store'])->name('citas.store');

    Route::get('/admin/lumina', [PersonalityTestController::class, 'show'])->name('personality-test.blade');
    Route::post('/admin/lumina', [PersonalityTestController::class, 'submit'])->name('personality-test.submit');
    
    
});
