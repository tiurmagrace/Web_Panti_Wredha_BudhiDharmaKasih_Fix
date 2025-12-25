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
        <input type="text" class="input-custom" v-model="form.donatur" placeholder="Nama donatur">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Jenis Bantuan</label>
        <select class="input-custom" v-model="form.jenis">
            <option value="">-- Pilih Jenis --</option>
            <option value="Barang">Barang</option>
            <option value="Tunai">Tunai</option>
        </select>
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Detail Bantuan</label>
        <template v-if="form.jenis === 'Barang'">
            <select class="input-custom" v-model="form.detail">
                <option disabled value="">-- Pilih Kategori --</option>
                <option value="Sembako">Sembako</option>
                <option value="Pakaian">Pakaian</option>
                <option value="Alat Kebersihan">Alat Kebersihan</option>
                <option value="Alat Kesehatan">Alat Kesehatan</option>
                <option value="Peralatan Rumah Tangga">Peralatan Rumah Tangga</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Perlengkapan Tidur">Perlengkapan Tidur</option>
                <option value="Buku & Hiburan">Buku & Hiburan</option>
                <option value="Perlengkapan Medis">Perlengkapan Medis</option>
                <option value="Lainnya">Lainnya</option>
                <!-- Tambahkan option untuk nilai custom yang tidak ada di list -->
                <option v-if="originalData && originalData.detail && !kategoriBarang.includes(originalData.detail)" 
                        :value="originalData.detail">@{{ originalData.detail }} (Custom)</option>
            </select>
        </template>
        <input v-else type="text" class="input-custom" v-model="form.detail" placeholder="Contoh: Transfer Bank BCA / Cash">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Jumlah</label>
        <input type="text" class="input-custom" v-model="form.jumlah" placeholder="Contoh: 10 Pcs / Rp 500.000">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Tanggal Distribusi</label>
        <input type="date" class="input-custom" v-model="form.tanggal">
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Status</label>
        <select class="input-custom" v-model="form.status">
            <option value="">-- Pilih Status --</option>
            <option value="Langsung">Langsung</option>
            <option value="Tidak Langsung">Tidak Langsung</option>
        </select>
    </div>
    
    <div class="form-group-row">
        <label class="label-custom">Petugas</label>
        <input type="text" class="input-custom" v-model="form.petugas" placeholder="Nama petugas penerima">
    </div>

    <div class="form-group-row">
        <label class="label-custom">Catatan</label>
        <textarea class="input-custom" v-model="form.catatan" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
    </div>
    
    <div class="form-group-row" style="align-items: flex-start;">
        <label class="label-custom" style="padding-top: 10px;">Bukti Penerimaan</label>
        <div>
            <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
            <div class="photo-upload-box" @click="$refs.fileInput.click()" style="cursor: pointer;">
                <img v-if="previewImage" :src="previewImage" class="photo-preview" style="max-width: 150px; max-height: 150px;">
                <div v-else class="text-center text-muted">
                    <i class="fas fa-image fa-3x mb-2" style="opacity: 0.3;"></i>
                    <br>Klik untuk upload
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-5">
        <button @click="goBack" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
        <button @click="validateAndSave" class="btn-submit-custom" :disabled="isLoading">
            <span v-if="isLoading"><i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...</span>
            <span v-else><i class="fas fa-save me-2"></i> Simpan Perubahan</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/edit-donasi.js') }}?v={{ time() }}"></script>
@endpush