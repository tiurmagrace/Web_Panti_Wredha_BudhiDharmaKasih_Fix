




{{-- ==========================================
     3. semua-feedback.blade.php
     ========================================== --}}
@extends('layouts.admin')

@section('title', 'Kotak Masuk Pesan & Feedback')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/semua-feedback.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="margin-bottom: 25px;">
    Kotak Masuk Pesan & Feedback
</div>

<div class="glass-panel">
    <div class="row mb-4 align-items-end g-2">
        <div class="col-md-3">
            <label class="filter-label">Filter Bulan</label>
            <select v-model="filterBulan" class="form-select form-select-sm">
                <option value="">Semua Bulan</option>
                <option value="01">Januari</option><option value="02">Februari</option><option value="03">Maret</option>
                <option value="04">April</option><option value="05">Mei</option><option value="06">Juni</option>
                <option value="07">Juli</option><option value="08">Agustus</option><option value="09">September</option>
                <option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="filter-label">Filter Tahun</label>
            <select v-model="filterTahun" class="form-select form-select-sm">
                <option value="">Semua Tahun</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
            </select>
        </div>
        <div class="col-md-2">
            <button v-if="filterBulan || filterTahun" @click="resetFilter" class="btn btn-danger btn-sm w-100" style="height: 31px;">
                <i class="fas fa-times"></i> Reset
            </button>
        </div>
    </div>
    <hr style="border-top: 1px solid #eee;">

    <div v-if="filteredList.length === 0" class="text-center py-5 text-muted">
        <i class="far fa-envelope-open fa-3x mb-3" style="opacity: 0.3;"></i>
        <br>
        Tidak ada pesan yang cocok dengan filter/pencarian.
    </div>
    
    <div v-else>
        <div v-for="(msg, index) in filteredList" :key="index" class="msg-item">
            <div class="msg-header d-flex justify-content-between align-items-center">
                <span class="sender-name">
                    <i class="fas fa-user-circle me-2" style="font-size: 1.2rem;"></i> @{{ msg.nama }}
                </span>
                <span class="msg-time">
                    <i class="far fa-calendar-alt me-1"></i> @{{ msg.tanggal }} &nbsp; 
                    <i class="far fa-clock me-1"></i> @{{ msg.jam || '10:00' }}
                </span>
            </div>
            <div class="msg-body">
                <i class="fas fa-quote-left me-2 text-muted" style="font-size: 0.8rem;"></i>
                @{{ msg.pesan }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/semua-feedback.js') }}"></script>
@endpush