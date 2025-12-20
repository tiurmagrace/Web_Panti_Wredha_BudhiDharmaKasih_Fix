@extends('layouts.admin')

@section('title', 'Tambah Donasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tambah-donasi.css') }}">
<style>
    .header-center { flex: 1; padding: 0; }
    .search-box { display: none; }
</style>
@endpush

@section('content')
<div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
    Tambah Donasi
</div>

<div class="form-container">
    <div class="section-header-teal">Data Distribusi</div>
    
    <div class="form-group-row">
        <label class="label-custom">Donatur</label>
        <input type="text" class="input-custom" v-model="form.donatur">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Jenis Bantuan</label>
        <select class="input-custom" v-model="form.jenis">
            <option value="" disabled selected>-- Pilih Jenis --</option>
            <option>Barang</option>
            <option>Tunai</option>
        </select>
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Detail Bantuan</label>
        <select v-if="form.jenis === 'Barang'" class="input-custom" v-model="form.detail">
            <option disabled value="">-- Pilih Kategori --</option>
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
        <input v-else type="text" class="input-custom" v-model="form.detail" placeholder="Contoh: Transfer Bank BCA / Cash">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Jumlah</label>
        <input type="text" class="input-custom" v-model="form.jumlah" placeholder="Contoh: 5 Karung / Rp 1.000.000">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Tanggal Distribusi</label>
        <input type="date" class="input-custom" v-model="form.tanggal_raw">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Status</label>
        <select class="input-custom" v-model="form.status">
            <option value="" disabled selected>-- Pilih Status --</option>
            <option>Langsung</option>
            <option>Tidak Langsung</option>
        </select>
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Petugas</label>
        <input type="text" class="input-custom" v-model="form.petugas" placeholder="Nama petugas penerima">
    </div>
    
    <div class="form-group-row" style="align-items: flex-start;">
        <label class="label-custom" style="padding-top: 10px;">Bukti Penerimaan</label>
        <div>
            <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
            <div class="photo-upload-box" @click="$refs.fileInput.click()">
                <img v-if="previewImage" :src="previewImage" class="photo-preview">
                <i v-else class="fas fa-camera camera-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="text-end mt-5">
        <button @click="validateAndSubmit" class="btn-submit-custom">
            <i class="fas fa-save me-2"></i> Submit
        </button>
    </div>
    
    <div v-if="showError" class="error-message">
        <i class="fas fa-exclamation-circle me-2"></i> Mohon lengkapi semua data distribusi terlebih dahulu!
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/tambah-donasi.js') }}"></script>
@endpush