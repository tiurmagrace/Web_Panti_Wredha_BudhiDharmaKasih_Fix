@extends('layouts.admin')

@section('title', 'Edit Data Donasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/edit-donasi.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
    Edit Data Donasi
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
        <input type="text" class="input-custom" v-model="form.jumlah">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Tanggal Distribusi</label>
        <input type="date" class="input-custom" v-model="form.tanggal_raw">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Status</label>
        <select class="input-custom" v-model="form.status">
            <option>Langsung</option>
            <option>Tidak Langsung</option>
        </select>
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Petugas</label>
        <input type="text" class="input-custom" v-model="form.petugas">
    </div>
    
    <div class="form-group-row" style="align-items: flex-start;">
        <label class="label-custom" style="padding-top: 10px;">Bukti Penerimaan</label>
        <div>
            <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
            <div class="photo-upload-box" @click="$refs.fileInput.click()">
                <img v-if="previewImage" :src="previewImage" class="photo-preview">
                <img v-else src="{{ asset('assets/images/1.png') }}" style="width: 50px; opacity: 0.5;">
            </div>
            <div class="text-center mt-1">*Unggah Gambar</div>
        </div>
    </div>
    
    <div class="text-end mt-5">
        <button @click="validateAndSave" class="btn-submit-custom">
            <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/edit-donasi.js') }}"></script>
@endpush