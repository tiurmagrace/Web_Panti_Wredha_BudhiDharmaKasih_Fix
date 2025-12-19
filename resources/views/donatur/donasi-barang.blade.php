@extends('layouts.app')

@section('title', 'Formulir Donasi Barang')

@section('content')
<div id="donasiApp" v-cloak>

    {{-- PAGE HEADER --}}
    <section class="page-header py-5 text-center">
        <div class="container">
            <h1 class="main-title">Formulir Donasi Barang</h1>
        </div>
    </section>

    {{-- FORM --}}
    <section class="pb-5" style="background-color: var(--color-light-bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="form-donasi-box">
                        <form @submit.prevent="kirimDonasi" novalidate>

                            <div class="mb-3">
                                <label class="form-label">Nama Donatur</label>
                                <input type="text"
                                       class="form-control"
                                       v-model="form.nama"
                                       readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="tel"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.hp }"
                                       v-model="form.hp"
                                       placeholder="+62-812-xxxx-xxxx">
                                <div class="invalid-feedback" v-if="errors.hp">
                                    @{{ errors.hp }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gmail (Opsional)</label>
                                <input type="email"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.email }"
                                       v-model="form.email">
                                <div class="invalid-feedback" v-if="errors.email">
                                    @{{ errors.email }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori Barang</label>
                                <select class="form-select" v-model="form.kategori">
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option>Sembako</option>
                                    <option>Pakaian</option>
                                    <option>Alat Kebersihan</option>
                                    <option>Alat Kesehatan</option>
                                    <option>Peralatan Rumah Tangga</option>
                                    <option>Elektronik</option>
                                    <option>Perlengkapan Tidur</option>
                                    <option>Buku & Hiburan</option>
                                    <option>Perlengkapan Medis</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Barang / Detail *</label>
                                <input type="text"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.barang }"
                                       v-model="form.barang"
                                       placeholder="Contoh: Beras, Mie Instan, Baju Layak Pakai">
                                <div class="invalid-feedback" v-if="errors.barang">
                                    @{{ errors.barang }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Upload Foto Barang / Resi Pengiriman *
                                </label>
                                <input type="file"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.fileBukti }"
                                       @change="handleFileUpload"
                                       accept="image/*">

                                <div class="invalid-feedback" v-if="errors.fileBukti">
                                    @{{ errors.fileBukti }}
                                </div>

                                <div class="form-text text-light opacity-75">
                                    Foto barang atau resi wajib dilampirkan sebagai verifikasi.
                                </div>

                                <div v-if="form.fileBukti" class="mt-2">
                                    <img :src="form.fileBukti"
                                         style="max-height:150px;border-radius:8px;border:2px solid white;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control"
                                          v-model="form.catatan"
                                          rows="3">
                                </textarea>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-kirim">
                                    Kirim
                                </button>
                            </div>

                        </form>
                    </div>

                    {{-- MAP INFO --}}
                    <div class="map-info-box text-center mt-5">
                        <p class="mb-2">
                            Klik link Google Maps berikut untuk mengirim atau mengantar donasi Anda
                        </p>
                        <a href="https://maps.google.com" target="_blank">
                            Lihat Lokasi di Google Maps
                        </a>
                        <p class="mt-3 mb-0">
                            Panti Wredha Budi Dharma Kasih<br>
                            Purbalingga, Jawa Tengah
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- MODAL BERHASIL --}}
    <div class="modal fade" id="modalSuccess" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-berhasil">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-end border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-center">
                    <div class="modal-icon-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4 class="modal-title">Donasi Anda Berhasil Kami Terima</h4>
                    <p class="modal-quote">⭐ "Terima Kasih Telah Menjadi Cahaya" ⭐</p>
                    <p>
                        Terima kasih @{{ form.nama || 'Kakak Baik' }},
                        Admin kami akan memverifikasi kiriman Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- LOGOUT MODAL --}}
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-danger">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
                    <p class="mb-0 fw-medium fs-5">Apakah Anda yakin ingin keluar?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button"
                            class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button"
                            class="btn btn-danger rounded-pill px-4"
                            @click="confirmLogout">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/donasi-barang.js') }}"></script>
@endpush
