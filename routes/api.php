<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;

// Users
Route::get('/users', [UserController::class, 'index']); 
Route::get('/users/{id}', [UserController::class, 'show']); 
Route::post('/users', [UserController::class, 'store']); 
Route::put('/users/{id}', [UserController::class, 'update']); 
Route::delete('/users/{id}', [UserController::class, 'destroy']); 

// Authentication
Route::post('/login', [AuthController::class, 'login']);

// Dashboard
Route::get('/dashboard/counts', [DashboardController::class, 'getCounts']);

// Employees
Route::get('/employees', [EmployeeController::class, 'index']); 
Route::get('/employees/{id}', [EmployeeController::class, 'show']); 
Route::post('/employees', [EmployeeController::class, 'store']); 
Route::put('/employees/{id}', [EmployeeController::class, 'update']); 
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

// Pelanggan (Customers)
Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);

// Distributor
Route::get('/distributors', [DistributorController::class, 'index']);
Route::post('/distributors', [DistributorController::class, 'store']);
Route::get('/distributors/{id}', [DistributorController::class, 'show']);
Route::put('/distributors/{id}', [DistributorController::class, 'update']);
Route::delete('/distributors/{id}', [DistributorController::class, 'destroy']);

// barang (items)
Route::get('barang', [BarangController::class, 'index']);
Route::post('barang', [BarangController::class, 'store']);
Route::get('barang/{id}', [BarangController::class, 'show']);
Route::put('barang/{id}', [BarangController::class, 'update']);
Route::delete('barang/{id}', [BarangController::class, 'destroy']);

// jasa
Route::get('/jasa', [JasaController::class, 'index']); 
Route::post('/jasa', [JasaController::class, 'store']); 
Route::get('/jasa/{id}', [JasaController::class, 'show']); 
Route::put('/jasa/{id}', [JasaController::class, 'update']); 
Route::delete('/jasa/{id}', [JasaController::class, 'destroy']);

// laporan
Route::post('/penjualan', [PenjualanController::class, 'store']); 
Route::get('/penjualan', [PenjualanController::class, 'index']); 
Route::get('/penjualan/{id}', [PenjualanController::class, 'show']); 
Route::put('/penjualan/{id}', [PenjualanController::class, 'update']); 
Route::delete('/penjualan/{id}', [PenjualanController::class, 'destroy']);
Route::get('/penjualans/today', [PenjualanController::class, 'getTodaySales']);
Route::post('/penjualan/monthly', [PenjualanController::class, 'getSalesByMonth']);


// kategori
Route::post('/kategori', [KategoriController::class, 'store']); 
Route::get('/kategori', [KategoriController::class, 'index']); 
Route::get('/kategori/{id}', [KategoriController::class, 'show']); 
Route::put('/kategori/{id}', [KategoriController::class, 'update']); 
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
