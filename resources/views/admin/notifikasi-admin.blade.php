{{-- ==========================================
     1. notifikasi-admin.blade.php
     ========================================== --}}
@extends('layouts.admin')

@section('title', 'Pusat Notifikasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/notifikasi.css') }}">
@endpush

@section('content')
<div class="page-title-banner text-center" style="margin-bottom: 20px;">
    Pusat Notifikasi
</div>

<div class="notif-list-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-dark fw-bold">
            <i class="fas fa-list me-1"></i> Total: @{{ filteredList.length }} Notifikasi
        </div>
        <div>
            <select v-model="filterType" class="filter-select">
                <option value="">Semua Tipe</option>
                <option value="donasi">Donasi Masuk</option>
                <option value="stok">Stok Menipis</option>
            </select>
        </div>
    </div>
    
    <div v-if="filteredList.length === 0" class="text-center py-5 text-muted">
        <i class="far fa-bell-slash fa-3x mb-3" style="opacity: 0.5;"></i>
        <p class="fw-bold">Tidak ada notifikasi yang cocok.</p>
    </div>

    <div v-else>
        <div v-for="(notif, index) in filteredList" :key="index" class="notif-item">
            <div class="notif-icon-box" :class="notif.type === 'stok' ? 'bg-soft-orange' : 'bg-soft-green'">
                <i v-if="notif.type === 'donasi'" class="fas fa-box-open"></i>
                <i v-else class="fas fa-exclamation-triangle"></i>
            </div>
            
            <div class="notif-content flex-grow-1">
                <h6>@{{ notif.title }}</h6>
                <p>@{{ notif.text }}</p>
            </div>

            <div class="notif-date">@{{ notif.dateDisplay }}</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/notifikasi.js') }}"></script>
@endpush
