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
    })->name('signup');

    Route::get('/lupa-password', function () {
        return view('auth.lupa-password-donatur');
    })->name('lupa-password');

    Route::get('/verifikasi-kode', function () {
        return view('auth.verifikasi-kode-donatur');
    })->name('verifikasi-kode');

    Route::get('/reset-password', function () {
        return view('auth.reset-password-baru-donatur');
    })->name('reset-password');

    Route::get('/reset-sukses', function () {
        return view('auth.reset-sukses-donatur');
    })->name('reset-sukses');

});


/*
|--------------------------------------------------------------------------
| DONATUR - HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/
Route::prefix('donatur')->group(function () {

    Route::get('/sejarah', fn () => view('donatur.sejarah'))->name('donatur.sejarah');
    Route::get('/visi-misi', fn () => view('donatur.visi-misi'))->name('donatur.visi-misi');
    Route::get('/fasilitas', fn () => view('donatur.fasilitas'))->name('donatur.fasilitas');
    Route::get('/persyaratan', fn () => view('donatur.persyaratan'))->name('donatur.persyaratan');
    Route::get('/kontak', fn () => view('donatur.kontak'))->name('donatur.kontak');

});


/*
|--------------------------------------------------------------------------
| DONATUR - HALAMAN BUTUH LOGIN (JS BASED AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('donatur')->group(function () {

    Route::get('/donasi', fn () => view('donatur.donasi'))->name('donatur.donasi');
    Route::get('/donasi-tunai', fn () => view('donatur.donasi-tunai'))->name('donatur.donasi-tunai');
    Route::get('/donasi-barang', fn () => view('donatur.donasi-barang'))->name('donatur.donasi-barang');
    Route::get('/notifikasi', fn () => view('donatur.notifikasi-donatur'))->name('donatur.notifikasi');

});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Auth Admin
    Route::get('/login', fn () => view('admin.auth.login-admin'))->name('admin.login');
    Route::get('/lupa-password', fn () => view('admin.auth.lupa-password-admin'))->name('admin.lupa-password');
    Route::get('/reset-password', fn () => view('admin.auth.reset-password-admin'))->name('admin.reset-password');
    
    // Dashboard & Pages (protected by JS auth)
    Route::get('/', fn () => view('admin.index-admin'))->name('admin.dashboard');
    Route::get('/kelola-penghuni', fn () => view('admin.kelola-penghuni'))->name('admin.kelola-penghuni');
    Route::get('/tambah-penghuni', fn () => view('admin.tambah-penghuni'))->name('admin.tambah-penghuni');
    Route::get('/kelola-donasi', fn () => view('admin.kelola-donasi'))->name('admin.kelola-donasi');
    Route::get('/tambah-donasi', fn () => view('admin.tambah-donasi'))->name('admin.tambah-donasi');
    Route::get('/edit-donasi', fn () => view('admin.edit-donasi'))->name('admin.edit-donasi');
    Route::get('/data-barang', fn () => view('admin.data-barang'))->name('admin.data-barang');
    Route::get('/tambah-barang', fn () => view('admin.tambah-barang'))->name('admin.tambah-barang');
    Route::get('/ambil-stok', fn () => view('admin.ambil-stok'))->name('admin.ambil-stok');
    Route::get('/laporan-donasi', fn () => view('admin.laporan-donasi'))->name('admin.laporan-donasi');
    Route::get('/generate-laporan', fn () => view('admin.generate-laporan'))->name('admin.generate-laporan');
    Route::get('/semua-feedback', fn () => view('admin.semua-feedback'))->name('admin.semua-feedback');
    Route::get('/semua-aktivitas', fn () => view('admin.semua-aktivitas'))->name('admin.semua-aktivitas');
    Route::get('/notifikasi', fn () => view('admin.notifikasi-admin'))->name('admin.notifikasi');

});
