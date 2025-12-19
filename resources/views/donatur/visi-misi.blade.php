@extends('layouts.app')

@section('title', 'Visi & Misi')

@section('content')
<div id="visiMisiApp" v-cloak>

    <main class="visi-misi-bg">
        <div class="container">
            <div class="content-center-wrapper text-center">

                {{-- MOTTO --}}
                <div class="mb-5 w-100">
                    <h2 class="vm-title">MOTTO</h2>
                    <hr class="vm-divider">
                    <p class="vm-text">"Kasih Kristus Untuk Semuanya"</p>
                </div>

                {{-- VISI --}}
                <div class="mb-5 w-100">
                    <h2 class="vm-title">VISI</h2>
                    <hr class="vm-divider">
                    <p class="vm-text">
                        "Menjadi suatu Yayasan Kristen yang memberikan Pelayanan Kasih
                        kepada sesama manusia, terutama kepada kaum tua agar mereka
                        dapat mencapai kehidupan yang berkualitas."
                    </p>
                </div>

                {{-- MISI --}}
                <div class="mb-5 w-100">
                    <h2 class="vm-title">MISI</h2>
                    <hr class="vm-divider">

                    <div class="d-inline-block text-start" style="max-width: 900px;">
                        <ol class="vm-list">
                            <li>
                                Memberi pelayanan Panti kepada orang tua agar mereka
                                menikmati hidup di usia senja dengan bahagia.
                            </li>
                            <li>
                                Memberikan pelayanan kesehatan, pelayanan rohani,
                                dan pelayanan rekreasi agar penghuni menikmati hidup
                                sehat sesuai usia.
                            </li>
                            <li>
                                Memberikan pelayanan lainnya yang menunjukkan kasih
                                Kristus tanpa bertentangan dengan peraturan
                                perundang-undangan.
                            </li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </main>

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
<script src="{{ asset('assets/js/profil-pages-vue.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof initProfilPageVue === 'function') {
            initProfilPageVue('visiMisiApp');
        }
    });
</script>
@endpush
