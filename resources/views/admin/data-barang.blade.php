@extends('layouts.admin')

@section('title', 'Data Stok Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/data-barang.css') }}">
@endpush

@section('content')
{{-- Vue mounting di sini (child app) --}}
<div id="barangApp">
    <div class="page-title-banner" style="background-color: #1a5c7a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 25px;">
        Data Stok Barang
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="stat-card bg-blue-mid">
                <div class="stat-number">@{{ totalStok }}</div>
                <div class="stat-label">Total Stok Item</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-blue-mid">
                <div class="stat-number">@{{ stokMenipis }}</div>
                <div class="stat-label">Stok Menipis</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-blue-mid">
                <div class="stat-number">@{{ jumlahHampirExpired }}</div>
                <div class="stat-label">Mendekati<br>Kadaluwarsa</div>
            </div>
        </div>
    </div>

    <div class="glass-panel" style="margin-top: 0; background-color: #ffffff; color: #333; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px;">
        <div class="row g-2 mb-3 align-items-end p-2" style="background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
            <div class="col-md-3">
                <label class="small fw-bold text-muted">Filter Berdasarkan:</label>
                <select v-model="filterType" class="form-select form-select-sm">
                    <option value="tgl_masuk">Tanggal Masuk</option>
                    <option value="expired">Tanggal Expired</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted">Dari Tanggal:</label>
                <input type="date" v-model="startDate" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted">Sampai Tanggal:</label>
                <input type="date" v-model="endDate" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 text-end">
                <button @click="resetFilter" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-sync-alt"></i> Reset Filter
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Tgl Masuk</th>
                        <th>Tgl Expired</th>
                        <th>Barang<br>masuk</th>
                        <th>Sisa<br>stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loading State --}}
                    <tr v-if="isLoading">
                        <td colspan="8" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Memuat data...</p>
                        </td>
                    </tr>
                    {{-- Data Rows --}}
                    <tr v-else v-for="(item, index) in paginatedList" :key="index">
                        <td>@{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
                        <td>@{{ item.nama }}</td>
                        <td>@{{ item.kategori }}</td>
                        <td>@{{ item.tgl_masuk }}</td>
                        <td>
                            <span v-if="!item.expired || item.expired === '-'">-</span>
                            <span v-else :class="{'text-danger fw-bold': isNearExpiry(item.expired)}">
                                @{{ item.expired }}
                                <i v-if="isNearExpiry(item.expired)" class="fas fa-exclamation-circle text-danger ms-1" title="Segera Expired!"></i>
                            </span>
                        </td>
                        <td :class="{'text-danger fw-bold': item.brg_masuk === '-'}">@{{ item.brg_masuk }}</td>
                        <td :class="{'text-danger fw-bold': item.sisa_stok === '-'}">@{{ item.sisa_stok }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <button @click="openModal(item, 'detail')" class="btn-action-custom" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button @click="openModal(item, 'edit')" class="btn-action-custom" title="Edit/Input Stok">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="deleteItem(item)" class="btn-action-custom" title="Hapus" style="color: #dc3545; border-color: #dc3545;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    {{-- Empty State --}}
                    <tr v-if="!isLoading && paginatedList.length === 0">
                        <td colspan="8" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end align-items-center mt-4" v-if="totalPages > 1">
            <button @click="prevPage" :disabled="currentPage === 1" class="btn btn-sm">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div v-for="page in totalPages" :key="page" @click="currentPage = page" :class="['pagination-box', { active: currentPage === page }]">
                @{{ page }}
            </div>
            <button @click="nextPage" :disabled="currentPage === totalPages" class="btn btn-sm">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- MODAL DETAIL/EDIT --}}
    <div v-if="isModalOpen" v-cloak class="modal-overlay" @click.self="closeModal">
        <div class="modal-container">
            <div class="modal-header">@{{ modalMode === 'detail' ? 'Detail Barang' : 'Edit Barang / Input Stok' }}</div>
            <div class="modal-body">
                <div v-if="modalMode === 'detail'">
                    <div class="detail-row">
                        <div class="detail-label">Nama Barang</div>
                        <div>: @{{ tempFormData.nama }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Kategori</div>
                        <div>: @{{ tempFormData.kategori }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Masuk</div>
                        <div>: @{{ tempFormData.tgl_masuk }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tgl Expired</div>
                        <div>: @{{ tempFormData.expired || '-' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Stok Masuk</div>
                        <div class="fw-bold">: @{{ tempFormData.brg_masuk }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Sisa Stok</div>
                        <div class="fw-bold" style="color: #1a5c7a;">: @{{ tempFormData.sisa_stok }}</div>
                    </div>
                </div>
                <div v-else>
                    <div class="alert alert-info py-2" style="font-size: 0.9rem;">
                        <i class="fas fa-info-circle"></i> Input jumlah barang real yang diterima.
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Nama Barang</div>
                        <input v-model="tempFormData.nama" class="edit-input">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Kategori</div>
                        <select v-model="tempFormData.kategori" class="edit-input">
                            <option>Sembako</option>
                            <option>Pakaian</option>
                            <option>Alat Kebersihan</option>
                            <option>Alat Kesehatan</option>
                            <option>Peralatan Rumah Tangga</option>
                            <option>Elektronik</option>
                            <option>Perlengkapan Tidur</option>
                            <option>Buku & Hiburan</option>
                            <option>Perlengkapan Medis</option>
                            <option>Lainnya</option>
                        </select>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tanggal Masuk</div>
                        <input type="date" v-model="tempFormData.tgl_masuk_raw" class="edit-input">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tgl Expired</div>
                        <input type="date" v-model="tempFormData.expired_raw" class="edit-input">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Stok Masuk</div>
                        <input v-model="tempFormData.brg_masuk" class="edit-input" placeholder="Contoh: 5 Dus / 100 Pcs">
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Sisa Stok</div>
                        <input v-model="tempFormData.sisa_stok" class="edit-input" placeholder="Samakan dengan Stok Masuk">
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button @click="closeModal" class="btn-action-modal btn-cancel-custom">
                        <i class="fas fa-times me-2"></i> Tutup
                    </button>
                    <button v-if="modalMode === 'edit'" @click="processEdit" class="btn-action-modal btn-save-custom">
                        <i class="fas fa-save me-2"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/data-barang.js') }}"></script>
@endpush