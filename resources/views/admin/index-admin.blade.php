@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="page-title-banner">
    Data Penghuni Panti Wredha "Budi Dharma Kasih" Purbalingga
</div>

{{-- Stats Cards Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="custom-card">
            <h2>@{{ totalPenghuni }}</h2>
            <p class="card-label">Jumlah Penghuni Saat Ini</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="custom-card">
            <h2>Rp. @{{ formatRupiah(totalUang) }}</h2>
            <p class="card-label">Donasi Tunai Bulan Ini</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="custom-card">
            <ul class="list-unstyled list-content-for-old-style">
                <li>• @{{ totalSembako }} Sembako</li>
                <li>• @{{ totalPakaian }} Pakaian</li>
                <li>• @{{ totalObat }} Obat-obatan</li>
            </ul>
            <p class="card-label">Donasi Barang Bulan Ini</p>
        </div>
    </div>
</div>

{{-- Info Boxes Row --}}
<div class="row g-4 mb-4 row-centered">
    <div class="col-md-6">
        <div class="info-box-middle">
            <div class="info-box-content">
                <div>💸 Uang Tunai : Rp @{{ formatRupiah(totalUang) }}</div>
                <div>🎁 Barang : @{{ totalBarang }} Item</div>
            </div>
            <div class="info-box-footer">Total Donasi Masuk (Realtime)</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-box-middle">
            <div class="info-box-content">
                <div>🎁 Total Stok Barang : @{{ totalStok }}</div>
                <div>🟠 Stok Menipis : @{{ stokMenipis }} Jenis</div>
                <div v-if="jumlahHampirExpired > 0" style="margin-top: 5px; color: #ffeb3b; font-weight: bold;">
                    ⚠️ Mendekati Expired : @{{ jumlahHampirExpired }} Jenis
                </div>
            </div>
            <div class="info-box-footer">Status Stok Gudang</div>
        </div>
    </div>
</div>

{{-- Feedback Table --}}
<div class="glass-panel">
    <div class="section-head">Pesan / Feedback dari Donatur</div>
    
    <div v-if="feedbacks.length === 0" class="text-center py-4 text-muted">
        Belum ada pesan masuk.
    </div>
    
    <table v-else class="table-transparent table-hover" style="cursor: pointer;">
        <thead>
            <tr>
                <th>Nama Donatur</th>
                <th>Tanggal</th>
                <th>Pesan</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in filteredFeedbacks" :key="item.id" @click="showFullMessage(item)">
                <td>@{{ item.nama }}</td>
                <td>@{{ item.tanggal }}</td>
                <td>@{{ truncateText(item.pesan, 50) }}</td>
            </tr>
        </tbody>
    </table>

    <div v-if="searchQuery && filteredFeedbacks.length === 0" class="text-center py-3 text-muted">
        Tidak ada pesan yang cocok dengan "@{{ searchQuery }}"
    </div>
    
    <div class="text-end mt-2 fw-bold" v-if="feedbacks.length > 0">
        <a href="{{ route('admin.semua-feedback') }}" class="text-decoration-none" style="color: #1a5c7a;">
            dst >>
        </a>
    </div>
</div>

{{-- Footer Panels (Activity & Notifications) --}}
<div class="footer-panels">
    
    {{-- Activity Panel --}}
    <div class="panel-box">
        <h5>AKTIVITAS TERAKHIR</h5>
        <div v-if="activities.length === 0" class="text-muted fst-italic">
            Belum ada aktivitas tercatat.
        </div>
        <ol v-else>
            <li v-for="(act, index) in filteredActivities" :key="index" class="mb-1">
                @{{ act.text }} 
                <span style="font-size: 0.75rem; color: #888;">(@{{ act.time }})</span>
            </li>
        </ol>
        <div v-if="searchQuery && filteredActivities.length === 0" class="text-center text-muted small">
            Tidak ada aktivitas "@{{ searchQuery }}"
        </div>
        <div class="text-end mt-2 fw-bold" v-if="activities.length > 0"> 
            <a href="{{ route('admin.semua-aktivitas') }}" class="text-decoration-none" style="color: #21698a;">
                dst >>
            </a>
        </div>
    </div>

    {{-- Notification Panel --}}
    <div class="panel-box">
        <h5>NOTIFIKASI TERBARU</h5>
        <div v-if="notifications.length === 0" class="text-muted fst-italic">
            Belum ada notifikasi baru.
        </div>
        <ol v-else>
            <li v-for="(notif, index) in filteredNotifications" :key="index" class="mb-2">
                <span v-if="notif.type === 'donasi'" style="color: #27ae60;">🟢</span> 
                <span v-else-if="notif.type === 'stok'" style="color: #e67e22;">🟠</span>
                @{{ notif.text }} 
            </li>
        </ol>
        <div v-if="searchQuery && filteredNotifications.length === 0" class="text-center text-muted small">
            Tidak ada notifikasi "@{{ searchQuery }}"
        </div>
        <div class="dst-mark" v-if="notifications.length > 5">
            <a href="{{ route('admin.notifikasi') }}" class="text-decoration-none fw-bold" style="color: #21698a;">
                dst >>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush