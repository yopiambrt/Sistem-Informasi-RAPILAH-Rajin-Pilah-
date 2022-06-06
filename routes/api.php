<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\EdukasiController;
use App\Http\Controllers\TrashTypesController;
use App\Http\Controllers\TrashTypeSnapshootController;
use App\Http\Controllers\AdressesController;
use App\Http\Controllers\PickupTransactionController;

Route::namespace('Auth')->group(function () {
    Route::post('register', 'RegisterController');
    Route::post('login', 'LoginController');
    Route::get('logout', 'LogoutController');
    Route::get('/edukasi', [EdukasiController::class, 'index']);
    Route::post('/alamat/create', [AdressesController::class, 'create']);
});

Route::get('user', 'UserController');
Route::post('/user/{id}/edit', [UserController::class], 'edit');
Route::get('/user/getprofile', [UserController::class], 'getProfile');
Route::get('/user/showprofile', [UserController::class, 'index']);
Route::post('/user/update', [UserController::class, 'update']);
Route::post('/user/check-password', [UserController::class, 'checkPassword']);
Route::post('/user/change-password', [UserController::class, 'changePassword']);
Route::get('/user/all', [UserController::class, 'all']);
Route::delete('/user/{id}', [UserController::class, 'delete']);

Route::get('/edukasi', [EdukasiController::class, 'index']);
Route::post('/edukasi/create', [EdukasiController::class, 'create']);
Route::put('/edukasi/{id}/update', [EdukasiController::class, 'update']);
Route::get('/edukasi/{id}/search', [EdukasiController::class, 'search']);
Route::delete('/edukasi/{id}/delete', [EdukasiController::class, 'delete']);

Route::get('/trashtypes', [TrashTypesController::class, 'index']);
Route::post('/trashtypes/create', [TrashTypesController::class, 'create']);
Route::post('/trashtypesnapshoot/create', [TrashTypeSnapshootController::class, 'create']);
Route::get('/alamat', [AdressesController::class, 'index']);
Route::post('/trashtypesnapshoot/latihancreate', [TrashTypeSnapshootController::class, 'latihancreate']);

Route::get('/alamat/{user_id}/show', [AdressesController::class, 'show']);
Route::get('/alamat/show', [AdressesController::class, 'showAlamat']);

Route::get('/pickuptransaction', [PickupTransactionController::class, 'show']);
Route::post('/pickuptransaction/create', [PickupTransactionController::class, 'create']);
Route::get('/alamattransaction', [PickupTransactionController::class, 'showAlamat']);

Route::post('/pickup/snapshoot', [PickupTransactionController::class, 'pickupSnapshoot']); // Testing Revisi Fitur Transaksi
Route::get('/pickup/snapshoot/view', [PickupTransactionController::class, 'viewSnapshoot']);
Route::post('/pickup/checkout', [PickupTransactionController::class, 'pickupCheckout']);

Route::get('petugas/pickup/show', [PetugasController::class, 'showPickupCustomer']);
Route::get('petugas/pickup/pilih', [PetugasController::class, 'pilihPickup']);

Route::group(
    ['middleware' => ['auth.role:admin']], // Bagian Route Check Role
    function () {
        Route::get('admin/transaction/show', [PickupTransactionController::class, 'showAlamatByAdmin']);
        Route::delete('admin/transaction/{id}', [PickupTransactionController::class, 'deletePickupByAdmin']);
        Route::get('admin/pengguna/hitung', [AdminController::class, 'hitungPengguna']);
        Route::get('admin/petugas/hitung', [AdminController::class, 'hitungPetugas']);
    }
);
