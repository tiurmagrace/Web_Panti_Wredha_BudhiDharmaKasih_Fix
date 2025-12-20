@extends('layouts.admin')

@section('title', 'Generate Laporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/generate-laporan.css') }}">
<style>
    .header-center { flex: 1; padding: 0; }
    .search-box { display: none; }
</style>
@endpush

@section('content')
<div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
    Generate Laporan
</div>

<div class="form-container">
    <div class="section-badge">Form Laporan</div>
    
    <div class="form-row-custom">
        <label class="label-custom">Email Donatur</label>
        <div class="input-area">
            <input type="email" class="input-text-custom" v-model="emailDonatur" placeholder="Contoh: donatur@email.com">
        </div>
    </div>
    
    <div class="form-row-custom">
        <label class="label-custom">Isi Laporan</label>
        <div class="input-area">
            <textarea class="textarea-custom" v-model="isiLaporan"></textarea>
        </div>
    </div>
    
    <div class="form-row-custom">
        <label class="label-custom">Upload Bukti Terima Donasi</label>
        <div class="input-area upload-wrapper">
            <div class="photo-box" @click="$refs.fileInput.click()">
                <img v-if="previewImage" :src="previewImage" class="photo-preview">
                <i v-else class="fas fa-camera camera-icon"></i>
            </div>
            <input type="file" ref="fileInput" @change="handleFileUpload" style="display: none;" accept="image/*">
            <div class="file-input-row">
                <button class="btn-choose" @click="$refs.fileInput.click()">Choose File</button>
                <span class="file-status">@{{ fileName || 'No File Chosen' }}</span>
            </div>
        </div>
    </div>
    
    <div class="text-end mt-5">
        <button @click="generateAndSendPDF" class="btn-kirim">
            <i class="fas fa-paper-plane me-2"></i> Kirim & Generate PDF
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="{{ asset('assets/js/generate-laporan.js') }}"></script>
@endpush