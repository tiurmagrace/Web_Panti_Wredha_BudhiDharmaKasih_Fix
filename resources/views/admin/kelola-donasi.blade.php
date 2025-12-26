@extends('layouts.admin')

@section('title', 'Riwayat Distribusi Donasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/kelola-donasi.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="margin-bottom: 25px;">
    Riwayat Distribusi Donasi Panti
</div>

{{-- Success Alerts --}}
<div v-if="alertStatus === 'success'" class="alert alert-info text-center fw-bold mb-4" style="background-color: #d1ecf1; color: #0c5460; border-radius: 50px;">
    Donasi baru berhasil di tambahkan <i class="fas fa-check-circle"></i>
</div>
<div v-if="alertStatus === 'edited'" class="alert alert-info text-center fw-bold mb-4" style="background-color: #d1ecf1; color: #0c5460; border-radius: 50px;">
    Data berhasil di edit <i class="fas fa-check-circle"></i>
</div>

<div class="glass-panel" style="margin-top: 0; background-color: #ffffff; color: #333; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px;">
    
    {{-- Tambah Donasi Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.tambah-donasi') }}" class="btn btn-lg text-white" style="background-color: #1a5c7a; border-radius: 5px; font-size: 1rem;">
            <i class="fas fa-plus me-2"></i> Tambah Donasi
        </a>
    </div>

    {{-- Filter Toggle Button --}}
    <button @click="showFilter = !showFilter" class="filter-toggle-btn">
        <i class="fas" :class="showFilter ? 'fa-filter-slash' : 'fa-filter'"></i> 
        @{{ showFilter ? 'Sembunyikan Filter' : 'Tampilkan Filter' }}
    </button>

    {{-- Filter Section --}}
    <div v-show="showFilter" class="filter-section">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-box me-1"></i> Jenis Bantuan</label>
                <select v-model="filterJenis" class="filter-select">
                    <option value="">Semua Jenis</option>
                    <option v-for="jenis in uniqueJenis" :key="jenis" :value="jenis">@{{ formatTitleCase(jenis) }}</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-info-circle me-1"></i> Status</label>
                <select v-model="filterStatus" class="filter-select">
                    <option value="">Semua Status</option>
                    <option v-for="status in uniqueStatus" :key="status" :value="status">@{{ formatTitleCase(status) }}</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-check-circle me-1"></i> Verifikasi</label>
                <select v-model="filterVerifikasi" class="filter-select">
                    <option value="">Semua</option>
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 mb-3 d-flex align-items-end">
                <button @click="resetFilter" class="btn-reset-filter w-100">
                    <i class="fas fa-redo me-2"></i> Reset Filter
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-calendar-alt me-1"></i> Dari Tanggal</label>
                <input type="date" v-model="filterTanggalMulai" class="filter-input">
            </div>
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-calendar-alt me-1"></i> Sampai Tanggal</label>
                <input type="date" v-model="filterTanggalSelesai" class="filter-input">
            </div>
            <div class="col-md-3 mb-3">
                <label class="filter-label"><i class="fas fa-user-tie me-1"></i> Nama Petugas</label>
                <select v-model="filterPetugas" class="filter-select">
                    <option value="">Semua Petugas</option>
                    <option v-for="petugas in uniquePetugas" :key="petugas" :value="petugas">@{{ formatTitleCase(petugas) }}</option>
                </select>
            </div>
        </div>
        
        {{-- Active Filters Badge --}}
        <div v-if="activeFiltersCount > 0" class="mt-3">
            <small class="text-muted"><i class="fas fa-filter me-1"></i> Filter Aktif:</small>
            <div class="mt-2">
                <span v-if="filterJenis" class="active-filter-badge">Jenis: @{{ formatTitleCase(filterJenis) }}</span>
                <span v-if="filterStatus" class="active-filter-badge">Status: @{{ formatTitleCase(filterStatus) }}</span>
                <span v-if="filterVerifikasi" class="active-filter-badge">Verifikasi: @{{ formatTitleCase(filterVerifikasi) }}</span>
                <span v-if="filterPetugas" class="active-filter-badge">Petugas: @{{ formatTitleCase(filterPetugas) }}</span>
                <span v-if="filterTanggalMulai || filterTanggalSelesai" class="active-filter-badge">
                    Tanggal: @{{ filterTanggalMulai || '...' }} s/d @{{ filterTanggalSelesai || '...' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Tanggal</th>
                    <th>Donatur</th>
                    <th>Jenis Bantuan</th>
                    <th>Detail Bantuan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Verifikasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loading State --}}
                <tr v-if="isLoading">
                    <td colspan="9" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat data...</p>
                    </td>
                </tr>
                {{-- Data Rows --}}
                <tr v-else v-for="(item, index) in paginatedList" :key="index">
                    <td>@{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                    <td>@{{ formatTanggal(item.tanggal) }}</td>
                    <td>@{{ formatTitleCase(item.donatur) }}</td>
                    <td>@{{ formatTitleCase(item.jenis) }}</td>
                    <td>@{{ formatTitleCase(item.detail) }}</td>
                    <td>@{{ item.jumlah }}</td>
                    <td>@{{ formatTitleCase(item.status) }}</td>
                    <td>
                        <span v-if="item.status_verifikasi === 'approved'" class="badge bg-success">
                            <i class="fas fa-check me-1"></i> Disetujui
                        </span>
                        <span v-else-if="item.status_verifikasi === 'rejected'" class="badge bg-danger">
                            <i class="fas fa-times me-1"></i> Ditolak
                        </span>
                        <span v-else class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i> Menunggu
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            {{-- Tombol Verifikasi (hanya untuk pending) --}}
                            <button v-if="item.status_verifikasi === 'pending'" 
                                    @click="verifyDonasi(item, 'approved')" 
                                    class="btn btn-sm btn-success" 
                                    title="Setujui">
                                <i class="fas fa-check"></i>
                            </button>
                            <button v-if="item.status_verifikasi === 'pending'" 
                                    @click="verifyDonasi(item, 'rejected')" 
                                    class="btn btn-sm btn-danger" 
                                    title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                            {{-- Tombol Kirim Terima Kasih (hanya untuk approved) --}}
                            <button v-if="item.status_verifikasi === 'approved' && item.user_id" 
                                    @click="sendThankYou(item)" 
                                    class="btn btn-sm btn-info" 
                                    title="Kirim Ucapan Terima Kasih">
                                <i class="fas fa-heart"></i>
                            </button>
                            {{-- Tombol Edit --}}
                            <button @click="goToEditPage(item)" class="btn-action-custom" title="Edit/Detail">
                                <i class="fas fa-file-invoice"></i>
                            </button>
                            {{-- Tombol Hapus --}}
                            <button @click="deleteDonasi(item)" class="btn btn-sm btn-outline-danger" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                {{-- Empty State --}}
                <tr v-if="!isLoading && paginatedList.length === 0">
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-search fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                        Data tidak ditemukan.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end align-items-center mt-4" v-if="totalPages > 1">
        <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-link text-dark text-decoration-none btn-sm">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div v-for="page in totalPages" :key="page" @click="currentPage = page" :class="['pagination-box', { active: currentPage === page }]">
            @{{ page }}
        </div>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-link text-dark text-decoration-none btn-sm">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/kelola-donasi.js') }}?v={{ time() }}"></script>
@endpush