{{-- ==========================================
     2. semua-aktivitas.blade.php
     ========================================== --}}
@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/semua-aktivitas.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="margin-bottom: 25px;">
    Riwayat Aktivitas Admin
</div>

<div class="glass-panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2 align-items-center">
            <i class="fas fa-filter text-muted"></i>
            <select v-model="filterKategori" class="filter-select">
                <option value="">Semua Aktivitas</option>
                <option value="Penghuni">Data Penghuni</option>
                <option value="Donasi">Distribusi Donasi</option>
                <option value="Barang">Stok Barang</option>
            </select>
        </div>
        <div class="text-muted small">
            Total: <b>@{{ filteredList.length }}</b> aktivitas
        </div>
    </div>

    <div v-if="filteredList.length === 0" class="text-center py-5 text-muted">
        <i class="fas fa-search fa-2x mb-3" style="opacity: 0.3;"></i><br>
        Tidak ada aktivitas yang cocok.
    </div>
    
    <div v-else>
        <div v-for="(act, index) in filteredList" :key="index" class="log-item">
            <div class="log-icon">
                <i v-if="act.text.toLowerCase().includes('donasi')" class="fas fa-box-open"></i>
                <i v-else-if="act.text.toLowerCase().includes('penghuni')" class="fas fa-user-injured"></i>
                <i v-else-if="act.text.toLowerCase().includes('barang')" class="fas fa-boxes"></i>
                <i v-else class="fas fa-history"></i>
            </div>
            <div class="log-content">
                <div class="log-text">@{{ act.text }}</div>
            </div>
            <div class="log-time">
                <i class="far fa-clock me-1"></i> @{{ formatTanggal(act.time) }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/semua-aktivitas.js') }}"></script>
@endpush