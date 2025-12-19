@extends('layouts.app')

@section('title', 'Sejarah Singkat')

@section('content')
<div id="sejarahApp" v-cloak>

    {{-- PAGE HEADER --}}
    <section class="page-header pt-5 pb-0 text-center">
        <div class="container">
            <h1 class="main-title">SEJARAH SINGKAT</h1>
            <p class="subtitle">Panti "Budi Dharma Kasih" Purbalingga</p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="profile-section pt-3 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <img src="{{ asset('assets/images/gedung panti.png') }}"
                         alt="Gedung Panti Budi Dharma Kasih"
                         class="img-fluid rounded shadow w-100 mb-5">

                    <div class="history-text">
                        <p>
                            Sejarah yayasan ini dimulai pada tahun 1972 ketika Bapak Lie Hok Tjan
                            (Budi Soedarma) memiliki visi mulia untuk mendirikan sebuah Panti Wredha.
                            Beliau menghibahkan sebagian tanah miliknya di Kalimanah, Purbalingga
                            sebagai wujud keseriusan visi tersebut.
                        </p>

                        <p>
                            Pada tanggal 2 Desember 1988, Yayasan Pelayanan Kristen
                            “Budi Dharma Kasih” resmi didirikan. Nama “Budi Dharma” dipilih
                            sebagai bentuk penghormatan kepada Bapak Lie Hok Tjan
                            yang menjadi inspirasi utama berdirinya yayasan.
                        </p>

                        <p>
                            Pada tanggal 24 Desember 1988, Ibu Lie Hok Tjan turut merealisasikan
                            cita-cita luhur tersebut dengan menghibahkan tanah untuk pembangunan
                            Panti Wredha.
                        </p>

                        <p>
                            Pada Februari 1989, yayasan ini mendapat dukungan dari
                            Ibu Ny Liek Ny Eling dan Ny Tiang sebagai pendiri dan penyandang dana,
                            hingga akhirnya unit pertama Panti Wredha selesai pada
                            23 September 1989.
                        </p>

                        <p>
                            Hingga kini, lebih dari tiga dekade kemudian, pengelolaan yayasan
                            dilanjutkan oleh generasi berikutnya dengan tetap setia
                            pada visi dan misi pelayanan yang telah diletakkan sejak awal.
                        </p>
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
            initProfilPageVue('sejarahApp');
        }
    });
</script>
@endpush
