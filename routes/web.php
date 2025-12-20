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


/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', fn () => view('admin.auth.login-admin'))->name('login');
    Route::get('/lupa-password', fn () => view('admin.auth.lupa-password-admin'))->name('lupa-password');
    Route::get('/reset-password', fn () => view('admin.auth.reset-password-admin'))->name('reset-password');
});


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD & PAGES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', fn () => view('admin.index-admin'))->name('dashboard');

    // Manajemen Penghuni
    Route::get('/kelola-penghuni', fn () => view('admin.kelola-penghuni'))->name('kelola-penghuni');
    Route::get('/tambah-penghuni', fn () => view('admin.tambah-penghuni'))->name('tambah-penghuni');

    // Manajemen Donasi
    Route::get('/kelola-donasi', fn () => view('admin.kelola-donasi'))->name('kelola-donasi');
    Route::get('/tambah-donasi', fn () => view('admin.tambah-donasi'))->name('tambah-donasi');
    Route::get('/edit-donasi', fn () => view('admin.edit-donasi'))->name('edit-donasi');
    Route::get('/laporan-donasi', fn () => view('admin.laporan-donasi'))->name('laporan-donasi');
    Route::get('/generate-laporan', fn () => view('admin.generate-laporan'))->name('generate-laporan');

    // Manajemen Barang
    Route::get('/data-barang', fn () => view('admin.data-barang'))->name('data-barang');
    Route::get('/tambah-barang', fn () => view('admin.tambah-barang'))->name('tambah-barang');
    Route::get('/ambil-stok', fn () => view('admin.ambil-stok'))->name('ambil-stok');

    // Notifikasi & Activity
    Route::get('/notifikasi', fn () => view('admin.notifikasi-admin'))->name('notifikasi');
    Route::get('/semua-aktivitas', fn () => view('admin.semua-aktivitas'))->name('semua-aktivitas');
    Route::get('/semua-feedback', fn () => view('admin.semua-feedback'))->name('semua-feedback');
});