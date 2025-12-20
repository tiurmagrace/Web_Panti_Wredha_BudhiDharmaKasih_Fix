@extends('layouts.admin')

@section('title', 'Tambah Stok Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tambah-barang.css') }}">
@endpush

@section('content')
{{-- Vue mounting di sini (child app) --}}
<div id="tambahBarangApp">
    <div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
        Tambah Barang Baru
    </div>

    <div class="form-container">
        <div class="section-header-teal">Informasi Barang</div>

        <div class="form-group-row">
            <label class="label-custom">Kategori</label>
            <select class="input-custom" v-model="form.kategori">
                <option value="" disabled selected>-- Pilih Kategori --</option>
                <option>Sembako</option>
                <option>Pakaian</option>
                <option>Alat Kebersihan</option>
                <option>Alat Kesehatan</option>
                <option>Peralatan Rumah Tangga</option>
                <option>Elektronik</option>
                <option>Perlengkapan Tidur</option>
                <option>Buku & Hiburan</option>
                <option>Perlengkapan Medis</option>
            </select>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Nama Barang</label>
            <input type="text" class="input-custom" v-model="form.nama">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Satuan</label>
            <select class="input-custom" v-model="form.satuan">
                <option>Pcs</option>
                <option>Pack</option>
                <option>Botol</option>
                <option>Karung</option>
                <option>Dus</option>
                <option>Strip</option>
                <option>Unit</option>
                <option>Sachet</option>
                <option>Bungkus</option>
            </select>
        </div>

        <div class="section-header-teal mt-4">Stok Awal</div>

        <div class="form-group-row">
            <label class="label-custom">Jumlah Stok Awal</label>
            <input type="number" class="input-custom" v-model="form.stok">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Tanggal Masuk</label>
            <input type="date" class="input-custom" v-model="form.tgl_masuk_raw">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Kondisi Barang</label>
            <select class="input-custom" v-model="form.kondisi">
                <option>Baik</option>
                <option>Rusak Ringan</option>
                <option>Perlu Perbaikan</option>
            </select>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Opsi Expired</label>
            <div style="flex: 1; display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="cekExpired" v-model="hasExpired" style="width: 20px; height: 20px; cursor: pointer;">
                <label for="cekExpired" style="cursor: pointer; color: #333;">Barang ini ada masa kadaluwarsa?</label>
            </div>
        </div>

        <div class="form-group-row" v-if="hasExpired">
            <label class="label-custom">Tgl. Kadaluwarsa</label>
            <input type="date" class="input-custom" v-model="form.expired_raw">
        </div>

        <div class="form-group-row" style="align-items: flex-start;">
            <label class="label-custom" style="padding-top: 10px;">Foto Barang</label>
            <div>
                <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
                <div class="photo-upload-box" @click="$refs.fileInput.click()">
                    <img v-if="previewImage" :src="previewImage" class="photo-preview">
                    <i v-else class="fas fa-camera camera-icon"></i>
                </div>
                <div class="text-center mt-1" style="font-size: 0.85rem; color: #666;">*Unggah Foto</div>
            </div>
        </div>

        <div class="text-end mt-5">
            <button @click="validateAndSubmit" class="btn-submit-custom">Simpan Data</button>
        </div>

        <div v-if="showError" class="error-message">
            Mohon lengkapi semua data barang (termasuk Kategori) terlebih dahulu!
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/tambah-barang.js') }}"></script>
@endpush