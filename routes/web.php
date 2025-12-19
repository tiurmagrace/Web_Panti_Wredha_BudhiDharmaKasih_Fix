<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| BERANDA
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('index');
})->name('home');


/*
|--------------------------------------------------------------------------
| AUTH DONATUR (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::get('/login', function () {
        return view('auth.login-donatur');
    })->name('login');

    Route::get('/signup', function () {
        return view('auth.signup-donatur');
    });

    Route::get('/lupa-password', function () {
        return view('auth.lupa-password-donatur');
    });

    Route::get('/verifikasi-kode', function () {
        return view('auth.verifikasi-kode-donatur');
    });

    Route::get('/reset-password', function () {
        return view('auth.reset-password-baru-donatur');
    });

    Route::get('/reset-sukses', function () {
        return view('auth.reset-sukses-donatur');
    });

});


/*
|--------------------------------------------------------------------------
| DONATUR - HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::prefix('donatur')->group(function () {

    Route::get('/sejarah', fn () => view('donatur.sejarah'));
    Route::get('/visi-misi', fn () => view('donatur.visi-misi'));
    Route::get('/fasilitas', fn () => view('donatur.fasilitas'));
    Route::get('/persyaratan', fn () => view('donatur.persyaratan'));
    Route::get('/kontak', fn () => view('donatur.kontak'));

});


/*
|--------------------------------------------------------------------------
| DONATUR - HALAMAN BUTUH LOGIN (JS BASED AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('donatur')->group(function () {

    Route::get('/donasi', fn () => view('donatur.donasi'));
    Route::get('/donasi-tunai', fn () => view('donatur.donasi-tunai'));
    Route::get('/donasi-barang', fn () => view('donatur.donasi-barang'));
    Route::get('/notifikasi', fn () => view('donatur.notifikasi-donatur'));

});
