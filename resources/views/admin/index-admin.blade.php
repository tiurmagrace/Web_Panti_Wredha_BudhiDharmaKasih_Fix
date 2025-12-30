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
            <h2>@{{ donasiTunaiBulanIni }} Donasi</h2>
            <p class="card-label">Donasi Tunai Bulan Ini</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="custom-card">
            <h2>@{{ totalDonasiBulanIni }} Donasi</h2>
            <ul class="list-unstyled list-content-for-old-style" style="font-size: 0.85rem; margin-top: 8px;" v-if="Object.keys(kategoriBulanIni).length > 0">
                <li v-for="(jumlah, kategori) in kategoriBulanIni" :key="kategori">
                    • @{{ jumlah }} @{{ kategori }}
                </li>
            </ul>
            <div v-else class="text-muted" style="font-size: 0.85rem; margin-top: 8px;">
                Belum ada donasi bulan ini
            </div>
            <p class="card-label">Donasi Masuk Bulan Ini</p>
        </div>
    </div>
</div>

{{-- Info Boxes Row --}}
<div class="row g-4 mb-4 row-centered">
    <div class="col-md-6">
        <div class="info-box-middle">
            <div class="info-box-content">
                <div>💸 Donasi Tunai : @{{ totalDonasiTunai }} Total</div>
                <div>🎁 Donasi Barang : @{{ totalDonasiBarang }} Item</div>
                <div v-if="pendingDonasi > 0" style="margin-top: 5px; cursor: pointer;" @click="goToPendingDonasi">
                    <span style="color: #ffeb3b;">⏳ Menunggu Verifikasi : @{{ pendingDonasi }}</span>
                    <small style="color: #aaa;">(klik untuk lihat)</small>
                </div>
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
                <td>@{{ formatDate(item.tanggal) }}</td>
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
                <span style="font-size: 0.75rem; color: #888;">(@{{ formatDate(act.time) }})</span>
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
        <h5>NOTIFIKASI TERBARU <span v-if="unreadCount > 0" class="badge bg-danger">@{{ unreadCount }}</span></h5>
        <div v-if="displayedNotifications.length === 0" class="text-muted fst-italic">
            Belum ada notifikasi baru.
        </div>
        <ol v-else>
            <li v-for="(notif, index) in displayedNotifications" :key="notif.id" class="mb-2" 
                :style="{ opacity: notif.status === 'read' ? 0.7 : 1 }"
                @click="markNotifAsRead(notif)" style="cursor: pointer;">
                <span v-if="notif.type && notif.type.includes('donasi')" style="color: #27ae60;">🟢</span> 
                <span v-else-if="notif.type && (notif.type.includes('stok') || notif.type.includes('kadaluarsa'))" style="color: #e67e22;">🟠</span>
                <span v-else style="color: #3498db;">🔵</span>
                @{{ notif.title }}: @{{ truncateText(notif.text, 40) }}
            </li>
        </ol>
        <div v-if="searchQuery && displayedNotifications.length === 0" class="text-center text-muted small">
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