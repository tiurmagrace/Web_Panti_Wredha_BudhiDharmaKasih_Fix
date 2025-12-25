@extends('layouts.admin')

@section('title', 'Laporan Donasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/laporan-donasi.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="margin-bottom: 25px;">
    Laporan Donasi Panti
</div>

{{-- Success Alerts --}}
<div v-if="alertStatus === 'sent'" class="alert alert-success text-center fw-bold mb-4" style="border-radius: 50px;">
    Laporan berhasil dikirim <i class="fas fa-check-circle"></i>
</div>

<div class="glass-panel" style="margin-top: 0; background-color: #ffffff; color: #333; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px;">
    
    {{-- Filter Section --}}
    <div class="filter-section mb-4">
        <div class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-search me-1"></i> Cari</label>
                <input type="text" v-model="searchQuery" class="form-control" placeholder="Cari donatur, jenis...">
            </div>
            <div class="col-md-2 mb-3">
                <label class="filter-label"><i class="fas fa-box me-1"></i> Jenis</label>
                <select v-model="filterJenis" class="form-select">
                    <option value="">Semua</option>
                    <option value="Barang">Barang</option>
                    <option value="Tunai">Tunai</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="filter-label"><i class="fas fa-calendar me-1"></i> Bulan</label>
                <select v-model="filterBulan" class="form-select">
                    <option value="">Semua</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="filter-label"><i class="fas fa-calendar-alt me-1"></i> Tahun</label>
                <select v-model="filterTahun" class="form-select">
                    <option value="">Semua</option>
                    <option v-for="year in availableYears" :key="year" :value="year">@{{ year }}</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <button @click="resetFilter" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-2"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Loading --}}
    <div v-if="isLoading" class="text-center py-5">
        <i class="fas fa-spinner fa-spin fa-2x"></i>
        <p class="mt-2">Memuat data...</p>
    </div>

    {{-- Table --}}
    <div v-else class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Tanggal</th>
                    <th>Donatur</th>
                    <th>Jenis</th>
                    <th>Detail</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Petugas</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in filteredList" :key="item.id">
                    <td>@{{ index + 1 }}</td>
                    <td>@{{ formatTanggal(item.tanggal) }}</td>
                    <td>@{{ item.donatur }}</td>
                    <td>@{{ item.jenis }}</td>
                    <td>@{{ item.detail }}</td>
                    <td>@{{ item.jumlah }}</td>
                    <td>@{{ item.status }}</td>
                    <td>@{{ item.petugas || '-' }}</td>
                    <td class="text-center">
                        <button @click="goToGeneratePage(item)" class="btn btn-sm btn-primary" title="Generate Laporan">
                            <i class="fas fa-file-pdf"></i> Generate
                        </button>
                    </td>
                </tr>
                <tr v-if="filteredList.length === 0">
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                        <span v-if="donasiList.length === 0">Belum ada donasi yang disetujui.</span>
                        <span v-else>Data tidak ditemukan.</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div v-if="filteredList.length > 0" class="mt-4 p-3 bg-light rounded">
        <div class="row text-center">
            <div class="col-md-4">
                <h5 class="mb-1">@{{ filteredList.length }}</h5>
                <small class="text-muted">Total Donasi</small>
            </div>
            <div class="col-md-4">
                <h5 class="mb-1">@{{ filteredList.filter(d => d.jenis === 'Tunai').length }}</h5>
                <small class="text-muted">Donasi Tunai</small>
            </div>
            <div class="col-md-4">
                <h5 class="mb-1">@{{ filteredList.filter(d => d.jenis === 'Barang').length }}</h5>
                <small class="text-muted">Donasi Barang</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/laporan-donasi.js') }}?v={{ time() }}"></script>
@endpush
