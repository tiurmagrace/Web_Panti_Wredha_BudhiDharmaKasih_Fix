@extends('layouts.admin')

@section('title', 'Data Penghuni')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/kelola-penghuni.css') }}">
@endpush

@section('content')
<div class="page-title-banner" style="margin-bottom: 25px;">
    Data Penghuni Panti Wredha "Budi Dharma Kasih" Purbalingga
</div>

<div class="glass-panel" style="margin-top: 0; background-color: #ffffff; color: #333; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px;">
    
    {{-- Filter Section --}}
    <div class="row mb-3 g-3 align-items-end">
        <div class="col-md-3">
            <label class="fw-bold small text-muted mb-1">Filter Paviliun</label>
            <select v-model="filterPaviliun" class="form-select border-primary text-primary" style="font-size: 0.9rem;">
                <option value="">Semua Paviliun</option>
                <option>ANGGREK</option>
                <option>BOUGENVILLE 1</option>
                <option>BOUGENVILLE 2</option>
                <option>MAWAR</option>
                <option>SNEEK</option>
                <option>BETHESDA</option>
                <option>DAHLIA</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="fw-bold small text-muted mb-1">Tahun Masuk</label>
            <select v-model="filterTahun" class="form-select border-primary text-primary" style="font-size: 0.9rem;">
                <option value="">Semua Tahun</option>
                <option v-for="thn in uniqueYears" :key="thn" :value="thn">@{{ thn }}</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="fw-bold small text-muted mb-1">Status Penghuni</label>
            <select v-model="filterStatusPenghuni" class="form-select border-primary text-primary" style="font-size: 0.9rem;">
                <option value="">Semua Status</option>
                <option value="Aktif">Masih di Panti</option>
                <option value="Keluar">Sudah Keluar</option>
                <option value="Meninggal">Meninggal</option>
            </select>
        </div>

        <div class="col-md-2">
            <button v-if="filterPaviliun || filterTahun || filterStatusPenghuni" @click="resetFilter" class="btn btn-outline-danger btn-sm w-100" style="height: 38px;">
                <i class="fas fa-times"></i> Reset Filter
            </button>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="table-responsive">
        <table class="table table-hover table-custom align-middle mb-0">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>TTL</th>
                    <th>Kota Asal</th>
                    <th>Tahun Masuk</th>
                    <th>Paviliun</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in paginatedList" :key="item.nik || index">
                    <td>@{{ item.nik }}</td>
                    <td>@{{ formatTitleCase(item.nama) }}</td>
                    <td>@{{ formatUpperCase(item.ttl) }}</td>
                    <td>@{{ formatUpperCase(item.kota) }}</td>
                    <td>@{{ item.tahun }}</td>
                    <td>@{{ formatUpperCase(item.paviliun) }}</td>
                    <td>
                        <span :class="getStatusClass(item.status_penghuni)">
                            @{{ item.status_penghuni || 'Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button @click="openModal(item, 'detail')" class="btn-action-custom" title="Detail">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <button @click="openModal(item, 'edit')" class="btn-action-custom" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="paginatedList.length === 0">
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
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

{{-- Modal Detail/Edit --}}
<div v-if="isModalOpen" 
     @click.self="closeModal" 
     style="position: fixed !important; 
            top: 0 !important; 
            left: 0 !important; 
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important; 
            height: 100vh !important; 
            background: rgba(0,0,0,0.75) !important; 
            z-index: 999999 !important;
            overflow-y: auto !important;
            padding: 120px 20px 40px 20px !important;
            display: flex !important;
            align-items: flex-start !important;
            justify-content: center !important;">
    <div style="width: 650px !important; 
                max-width: 95% !important; 
                background-color: #1a5c7a !important; 
                border-radius: 20px !important; 
                box-shadow: 0 20px 60px rgba(0,0,0,0.6) !important; 
                position: relative !important;
                padding-bottom: 30px !important;
                animation: modalSlideIn 0.3s ease-out !important;">
        
        {{-- Photo Frame --}}
        <div class="photo-frame-square" 
             @click="triggerFileInput"
             :style="{ cursor: modalMode === 'edit' ? 'pointer' : 'default' }">
            <img v-if="tempFormData.foto" :src="tempFormData.foto" class="photo-img">
            <i v-else class="fas fa-camera"></i>
        </div>
        <input type="file" ref="fileInput" style="display: none;" accept="image/*" @change="handlePhotoUpload">
        
        <div class="detail-white-box">
            
            {{-- Mode Detail --}}
            <div v-if="modalMode === 'detail'">
                <div class="modal-section-title">Detail Penghuni</div>
                <div class="detail-row"><div class="detail-label">NIK</div><div class="detail-value">: @{{ tempFormData.nik }}</div></div>
                <div class="detail-row"><div class="detail-label">Nama Lengkap</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.nama) }}</div></div>
                <div class="detail-row"><div class="detail-label">TTL</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.ttl) }} (@{{ tempFormData.usia }} Thn)</div></div>
                <div class="detail-row"><div class="detail-label">Kota Asal</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.kota) }}</div></div>
                <div class="detail-row"><div class="detail-label">Alamat</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.alamat) }}</div></div>
                <div class="detail-row"><div class="detail-label">Agama</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.agama) }}</div></div>
                <div class="detail-row"><div class="detail-label">Gender</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.gender) }}</div></div>
                <div class="detail-row"><div class="detail-label">Status</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.status) }}</div></div>
                
                <div class="modal-section-title">Data Kontak Darurat</div>
                <div class="detail-row"><div class="detail-label">Nama PJ</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.pj) }} (@{{ formatTitleCase(tempFormData.hubungan) }})</div></div>
                <div class="detail-row"><div class="detail-label">No. Telepon</div><div class="detail-value">: @{{ tempFormData.telp }}</div></div>
                <div class="detail-row"><div class="detail-label">Alamat PJ</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.alamat_pj) }}</div></div>

                <div class="modal-section-title">Data Kesehatan</div>
                <div class="detail-row"><div class="detail-label">Status Kesehatan</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.status_sehat) }}</div></div>
                <div class="detail-row"><div class="detail-label">Penyakit</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.penyakit) }}</div></div>
                <div class="detail-row"><div class="detail-label">Alergi</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.alergi) }}</div></div>
                <div class="detail-row"><div class="detail-label">Kebutuhan Khusus</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.kebutuhan) }}</div></div>
                <div class="detail-row"><div class="detail-label">Obat</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.obat) }}</div></div>
                
                <div class="modal-section-title">Data Panti</div>
                <div class="detail-row"><div class="detail-label">Tgl Masuk</div><div class="detail-value">: @{{ tempFormData.tgl_masuk }}</div></div>
                <div class="detail-row"><div class="detail-label">Sumber Rujukan</div><div class="detail-value">: @{{ tempFormData.rujukan }}</div></div>
                <div class="detail-row"><div class="detail-label">Paviliun</div><div class="detail-value">: @{{ formatTitleCase(tempFormData.paviliun) }}</div></div>
                <div class="detail-row"><div class="detail-label">Status Penghuni</div><div class="detail-value">: <span :class="getStatusClass(tempFormData.status_penghuni)">@{{ tempFormData.status_penghuni || 'Aktif' }}</span></div></div>
                <div v-if="tempFormData.status_penghuni === 'Keluar' || tempFormData.status_penghuni === 'Meninggal'" class="detail-row">
                    <div class="detail-label">Tgl Keluar</div><div class="detail-value">: @{{ tempFormData.tgl_keluar }}</div>
                </div>
                <div v-if="tempFormData.alasan_keluar" class="detail-row">
                    <div class="detail-label">Alasan</div><div class="detail-value">: @{{ tempFormData.alasan_keluar }}</div>
                </div>

                <div class="modal-section-title">Catatan</div>
                <div class="detail-row"><div class="detail-label">Catatan Khusus</div><div class="detail-value">: @{{ tempFormData.catatan }}</div></div>
            </div>

            {{-- Mode Edit --}}
            <div v-else>
                <div class="modal-section-title" style="margin-top:0;">Edit Data Penghuni</div>
                
                <div class="detail-row"><div class="detail-label">NIK</div><div class="detail-value"><input type="number" v-model="tempFormData.nik" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Nama Lengkap</div><div class="detail-value"><input type="text" v-model="tempFormData.nama" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">TTL</div><div class="detail-value"><input type="text" v-model="tempFormData.ttl" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Usia</div><div class="detail-value"><input type="number" v-model="tempFormData.usia" class="edit-input" style="width: 80px;"> Thn</div></div>
                <div class="detail-row"><div class="detail-label">Kota Asal</div><div class="detail-value"><input type="text" v-model="tempFormData.kota" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Alamat</div><div class="detail-value"><input type="text" v-model="tempFormData.alamat" class="edit-input"></div></div>
                
                <div class="detail-row">
                    <div class="detail-label">Agama</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.agama" class="edit-input">
                            <option>Kristen Protestan</option>
                            <option>Katholik</option>
                            <option>Islam</option>
                            <option>Hindu</option>
                            <option>Budha</option>
                            <option>Konghucu</option>
                            <option>Kepercayaan Terhadap Tuhan YME</option>
                            <option>Tidak Beragama</option>
                        </select>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Gender</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.gender" class="edit-input">
                            <option>Pria</option>
                            <option>Wanita</option>
                        </select>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.status" class="edit-input">
                            <option>Belum Kawin</option>
                            <option>Kawin</option>
                            <option>Janda</option>
                            <option>Duda</option>
                        </select>
                    </div>
                </div>

                <div class="modal-section-title">Data Kontak Darurat</div>
                <div class="detail-row"><div class="detail-label">Nama PJ</div><div class="detail-value"><input type="text" v-model="tempFormData.pj" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Hubungan</div><div class="detail-value"><input type="text" v-model="tempFormData.hubungan" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">No. Telepon</div><div class="detail-value"><input type="text" v-model="tempFormData.telp" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Alamat PJ</div><div class="detail-value"><input type="text" v-model="tempFormData.alamat_pj" class="edit-input"></div></div>

                <div class="modal-section-title">Data Kesehatan</div>
                <div class="detail-row"><div class="detail-label">Status Kesehatan</div><div class="detail-value"><input type="text" v-model="tempFormData.status_sehat" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Penyakit</div><div class="detail-value"><input type="text" v-model="tempFormData.penyakit" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Alergi</div><div class="detail-value"><input type="text" v-model="tempFormData.alergi" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Kebutuhan Khusus</div><div class="detail-value"><input type="text" v-model="tempFormData.kebutuhan" class="edit-input"></div></div>
                <div class="detail-row"><div class="detail-label">Obat</div><div class="detail-value"><input type="text" v-model="tempFormData.obat" class="edit-input"></div></div>

                <div class="modal-section-title">Data Panti</div>
                <div class="detail-row"><div class="detail-label">Tgl Masuk</div><div class="detail-value"><input type="date" v-model="tempFormData.tgl_masuk" class="edit-input"></div></div>
                <div class="detail-row">
                    <div class="detail-label">Sumber Rujukan</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.rujukan" class="edit-input">
                            <option>Yang Bersangkutan Sendiri</option>
                            <option>Kerabat/Tetangga</option>
                            <option>Dinas Sosial</option>
                            <option>Lembaga Kesehatan</option>
                            <option>Komunitas sosial</option>
                            <option>Pusat layanan terpadu</option>
                            <option>Lembaga Keagamaan</option>
                        </select>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Paviliun</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.paviliun" class="edit-input">
                            <option>ANGGREK</option>
                            <option>BOUGENVILLE 1</option>
                            <option>BOUGENVILLE 2</option>
                            <option>MAWAR</option>
                            <option>SNEEK</option>
                            <option>BETHESDA</option>
                            <option>DAHLIA</option>
                        </select>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status Penghuni</div>
                    <div class="detail-value">
                        <select v-model="tempFormData.status_penghuni" class="edit-input">
                            <option value="Aktif">Masih di Panti</option>
                            <option value="Keluar">Sudah Keluar</option>
                            <option value="Meninggal">Meninggal</option>
                        </select>
                    </div>
                </div>
                <div v-if="tempFormData.status_penghuni === 'Keluar' || tempFormData.status_penghuni === 'Meninggal'" class="detail-row">
                    <div class="detail-label">Tgl Keluar</div>
                    <div class="detail-value"><input type="date" v-model="tempFormData.tgl_keluar" class="edit-input"></div>
                </div>
                <div v-if="tempFormData.status_penghuni === 'Keluar' || tempFormData.status_penghuni === 'Meninggal'" class="detail-row">
                    <div class="detail-label">Alasan</div>
                    <div class="detail-value"><input type="text" v-model="tempFormData.alasan_keluar" class="edit-input" placeholder="Alasan keluar/meninggal"></div>
                </div>

                <div class="modal-section-title">Catatan</div>
                <div class="detail-row"><div class="detail-label">Catatan Khusus</div><div class="detail-value"><textarea v-model="tempFormData.catatan" class="edit-input" rows="2"></textarea></div></div>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button @click="closeModal" class="btn-modal-action me-2" style="background-color: #102f3e;">Kembali</button>
                <button v-if="modalMode === 'edit'" @click="processEdit" class="btn-modal-action" style="background-color: #21698a;">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/kelola-penghuni.js') }}"></script>
@endpush