@extends('layouts.app')

@section('title', 'Persyaratan Calon Penghuni')

@section('content')
<div id="persyaratanApp" v-cloak>

    {{-- HERO IMAGE --}}
    <div class="hero-image-requirements">
        <img src="{{ asset('assets/images/persyaratan.png') }}"
             alt="Header Persyaratan"
             class="w-100 h-auto"
             style="object-fit: cover;">
    </div>

    {{-- CONTENT --}}
    <section class="content-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="content-paper">
                        <h2>Siapa yang dapat menjadi penghuni panti?</h2>

                        <p class="intro-text">
                            Kami dengan tangan terbuka menyambut para lansia yang ingin bergabung
                            dan menjadi bagian dari keluarga besar kami. Untuk menjaga kenyamanan
                            dan keselamatan bersama, ada beberapa syarat yang perlu dipenuhi oleh
                            calon penghuni:
                        </p>

                        <ol class="requirements-list">
                            <li>Berusia 60 tahun ke atas.</li>
                            <li>Tidak mengidap gangguan kejiwaan, pikun berat, atau penyakit menular.</li>
                            <li>Melampirkan hasil tes kesehatan dari dokter, radiolog, atau laboratorium medis.</li>
                            <li>Mandiri secara fisik dan mampu melakukan aktivitas sehari-hari.</li>
                            <li>Memiliki sponsor atau penanggung jawab dari pihak keluarga.</li>
                            <li>Sponsor bersedia menanggung biaya dan rutin menjenguk calon penghuni.</li>
                            <li>Sponsor wajib menandatangani Surat Pernyataan Tanggung Jawab.</li>
                            <li>Ketentuan lainnya dapat dirundingkan bersama pengelola panti.</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
    </section>

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
            initProfilPageVue('persyaratanApp');
        }
    });
</script>
@endpush
