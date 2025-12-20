@extends('layouts.app')

@section('title', 'Formulir Donasi Tunai')

@section('content')
<div id="tunaiApp" v-cloak>

    {{-- PAGE HEADER --}}
    <section class="page-header py-5 text-center">
        <div class="container">
            <h1 class="main-title">Formulir Donasi Tunai</h1>
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
                                <label class="form-label">Nomor HP *</label>
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

                            {{-- ✅ TAMBAHAN: Field Jumlah Uang --}}
                            <div class="mb-3">
                                <label class="form-label">Jumlah Donasi (Rp) *</label>
                                <input type="text"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.jumlah }"
                                       v-model="form.jumlah"
                                       placeholder="Contoh: 500.000 atau 1.000.000">
                                <div class="invalid-feedback" v-if="errors.jumlah">
                                    @{{ errors.jumlah }}
                                </div>
                                <div class="form-text text-light opacity-75">
                                    Masukkan nominal tanpa "Rp" atau titik pemisah
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload Bukti Donasi *</label>
                                <input type="file"
                                       class="form-control"
                                       :class="{ 'is-invalid': errors.file }"
                                       @change="handleFileUpload"
                                       accept="image/*">
                                <div class="invalid-feedback" v-if="errors.file">
                                    @{{ errors.file }}
                                </div>
                                <div class="form-text text-light opacity-75">
                                    Format: PNG, JPG, JPEG
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control"
                                          v-model="form.catatan"
                                          rows="3"
                                          placeholder="Semoga dapat membantu...">
                                </textarea>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-kirim">
                                    Kirim
                                </button>
                            </div>

                        </form>
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
                    <h4 class="modal-title">Donasi Tunai Berhasil</h4>
                    <p class="modal-quote">⭐ "Terima Kasih Telah Menjadi Cahaya" ⭐</p>
                    <p>Terima kasih @{{ form.nama }}, bukti transfer anda telah kami terima.</p>
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
<script src="{{ asset('assets/js/donasi-tunai.js') }}"></script>
@endpush