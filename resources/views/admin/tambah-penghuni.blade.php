@extends('layouts.admin')

@section('title', 'Tambah Data Penghuni')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/tambah-penghuni.css') }}">
<style>
    /* Override header center untuk halaman ini */
    .header-center {
        flex: 1;
        padding: 0;
    }
    .search-box {
        display: none; /* Sembunyikan search box di halaman ini */
    }
</style>
@endpush

@section('content')
<div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
    Data Penghuni Baru
</div>

{{-- Progress Indicator --}}
<div class="progress-indicator">
    <div class="progress-step">
        <div :class="['step-circle', { active: step === 1, completed: step > 1 }]">
            <i v-if="step > 1" class="fas fa-check"></i>
            <span v-else>1</span>
        </div>
        <span :class="['step-label', { active: step === 1 }]">Data Pribadi</span>
    </div>
    
    <div :class="['step-divider', { completed: step > 1 }]"></div>
    
    <div class="progress-step">
        <div :class="['step-circle', { active: step === 2, completed: step > 2 }]">
            <i v-if="step > 2" class="fas fa-check"></i>
            <span v-else>2</span>
        </div>
        <span :class="['step-label', { active: step === 2 }]">Kontak & Kesehatan</span>
    </div>
    
    <div :class="['step-divider', { completed: step > 2 }]"></div>
    
    <div class="progress-step">
        <div :class="['step-circle', { active: step === 3 }]">3</div>
        <span :class="['step-label', { active: step === 3 }]">Data Panti</span>
    </div>
</div>

{{-- Form Container --}}
<div class="form-container">
    
    {{-- STEP 1: Data Pribadi --}}
    <div v-show="step === 1">
        <div class="section-header-teal">Data Pribadi</div>
        
        <div class="form-group-row">
            <label class="label-custom">Nama Lengkap</label>
            <input type="text" class="input-custom" v-model="form.nama">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">NIK</label>
            <input type="text" class="input-custom" v-model="form.nik" @input="filterAngka('nik')" maxlength="16" placeholder="Harus 16 Digit Angka">
        </div>
        <span v-if="form.nik && form.nik.length < 16" class="error-text-small">* NIK belum 16 digit</span>

        <div class="form-group-row">
            <label class="label-custom">Tempat, Tanggal, Lahir</label>
            <input type="text" class="input-custom" v-model="form.ttl">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Usia</label>
            <input type="number" class="input-custom" v-model="form.usia">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Jenis Kelamin</label>
            <select class="input-custom" v-model="form.gender">
                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                <option>Pria</option>
                <option>Wanita</option>
            </select>
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Agama</label>
            <select class="input-custom" v-model="form.agama">
                <option value="" disabled selected>-- Pilih Agama --</option>
                <option>Kristen Protestan</option>
                <option>Katholik</option>
                <option>Islam</option>
                <option>Hindu</option>
                <option>Budha</option>
                <option>Konghucu</option>
                <option>Kepercayaan Terhadap Tuhan YME</option>
                <option>Tidak Beragama</option>
            </select>
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Status Perkawinan</label>
            <select class="input-custom" v-model="form.status">
                <option value="" disabled selected>-- Pilih Status Perkawinan --</option>
                <option>Belum Kawin</option>
                <option>Kawin</option>
                <option>Janda</option>
                <option>Duda</option>
            </select>
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Alamat Lengkap</label>
            <input type="text" class="input-custom" v-model="form.alamat">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Kota Asal</label>
            <input type="text" class="input-custom" v-model="form.kota">
        </div>
    </div>

    {{-- STEP 2: Kontak & Kesehatan --}}
    <div v-show="step === 2">
        <div class="section-header-teal">Kontak Darurat</div>
        
        <div class="form-group-row">
            <label class="label-custom">Penanggung Jawab</label>
            <input type="text" class="input-custom" v-model="form.pj">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Hubungan</label>
            <input type="text" class="input-custom" v-model="form.hubungan">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Nomor Telepon</label>
            <input type="text" class="input-custom" v-model="form.telp" @input="filterAngka('telp')" placeholder="Hanya Angka">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Alamat Kontak</label>
            <input type="text" class="input-custom" v-model="form.alamat_pj">
        </div>

        <div class="section-header-teal mt-4">Data Kesehatan</div>
        
        <div class="form-group-row">
            <label class="label-custom">Riwayat Penyakit</label>
            <input type="text" class="input-custom" v-model="form.penyakit">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Kebutuhan Khusus</label>
            <input type="text" class="input-custom" v-model="form.kebutuhan">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Alergi</label>
            <input type="text" class="input-custom" v-model="form.alergi">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Obat</label>
            <input type="text" class="input-custom" v-model="form.obat">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Status Kesehatan</label>
            <input type="text" class="input-custom" v-model="form.status_sehat">
        </div>
    </div>

    {{-- STEP 3: Data Panti --}}
    <div v-show="step === 3">
        <div class="section-header-teal">Data Masuk Panti</div>
        
        <div class="form-group-row">
            <label class="label-custom">Tanggal Masuk</label>
            <input type="date" class="input-custom" v-model="form.tgl_masuk">
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Sumber Rujukan</label>
            <select class="input-custom" v-model="form.rujukan">
                <option value="" disabled selected>-- Pilih Sumber Rujukan --</option>
                <option>Yang Bersangkutan Sendiri</option>
                <option>Kerabat/Tetangga</option>
                <option>Dinas Sosial</option>
                <option>Lembaga Kesehatan</option>
                <option>Komunitas sosial</option>
                <option>Pusat layanan terpadu</option>
                <option>Lembaga Keagamaan</option>
            </select>
        </div>
        
        <div class="form-group-row">
            <label class="label-custom">Penempatan Paviliun</label>
            <select class="input-custom" v-model="form.paviliun">
                <option value="" disabled selected>-- Pilih Paviliun --</option>
                <option>ANGGREK</option>
                <option>BOUGENVILLE 1</option>
                <option>BOUGENVILLE 2</option>
                <option>MAWAR</option>
                <option>SNEEK</option>
                <option>BETHESDA</option>
                <option>DAHLIA</option>
            </select>
        </div>
        
        <div class="form-group-row" style="align-items: flex-start;">
            <label class="label-custom" style="padding-top: 10px;">Upload Foto</label>
            <div>
                <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
                <div class="photo-upload-box" @click="$refs.fileInput.click()">
                    <img v-if="previewImage" :src="previewImage" class="photo-preview">
                    <i v-else class="fas fa-camera camera-icon"></i>
                </div>
            </div>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Catatan</label>
            <input type="text" class="input-custom" v-model="form.catatan">
        </div>
    </div>

    {{-- Navigation Buttons --}}
    <div class="nav-buttons">
        <button v-if="step > 1" @click="step--" class="btn-nav-text">
            <i class="fas fa-chevron-left fa-lg"></i> Sebelumnya
        </button>
        <div v-if="step === 1" style="width: 20px;"></div>
        
        <button v-if="step < 3" @click="nextStep" class="btn-nav-text">
            Selanjutnya <i class="fas fa-chevron-right fa-lg"></i>
        </button>
        
        <button v-if="step === 3" @click="validateAndSubmit" class="btn-submit-custom">
            <i class="fas fa-check-circle"></i> Submit Data
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/tambah-penghuni.js') }}"></script>
@endpush