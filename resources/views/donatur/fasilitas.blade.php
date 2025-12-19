@extends('layouts.app')

@section('title', 'Fasilitas Panti')

@section('content')
<div id="fasilitasApp" v-cloak>

    {{-- PAGE HEADER --}}
    <section class="page-header">
        <div class="container text-center">
            <h1 class="main-title">FASILITAS PANTI</h1>
            <p class="subtitle">Sarana penunjang kenyamanan para lansia di panti</p>
        </div>
    </section>

    {{-- FASILITAS LIST --}}
    <section class="py-5 pt-4">
        <div class="container">

            {{-- ITEM --}}
            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 14.55.10_531d29d0 2.png') }}"
                         alt="Kamar Tidur">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Kamar Tidur</h3>
                        <p>
                            Setiap kamar tidur dilengkapi fasilitas yang menunjang kenyamanan penghuni,
                            seperti kasur bersih, meja kursi, kipas angin, serta pispot
                            untuk membantu aktivitas lansia.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.03.58_c6449bc5 2.png') }}"
                         alt="Paviliun Bougenville">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Paviliun Bougenville Khusus Pria</h3>
                        <p>
                            Paviliun pria dirancang untuk menciptakan lingkungan yang tenang,
                            bersih, dan nyaman bagi lansia laki-laki.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.19.26_174b21bc 2.png') }}"
                         alt="Paviliun Sneek">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Paviliun Sneek Khusus Wanita</h3>
                        <p>
                            Paviliun wanita menghadirkan suasana hangat dan tertata,
                            mendukung kenyamanan serta keamanan penghuni perempuan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.06.26_76bf93bb 1.png') }}"
                         alt="Paviliun Mawar">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Paviliun Mawar Khusus Wanita</h3>
                        <p>
                            Paviliun ini menyediakan ruang yang nyaman dan aman
                            untuk menunjang aktivitas harian lansia perempuan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.03.57_49955f94 1.png') }}"
                         alt="Paviliun Temanggung">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Paviliun Temanggung Khusus Pasangan</h3>
                        <p>
                            Paviliun pasangan dirancang untuk menjaga privasi,
                            kenyamanan, dan keharmonisan suami istri lansia.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.03.58_1f7b7957 1.png') }}"
                         alt="Ruang Aula">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Ruang Aula</h3>
                        <p>
                            Digunakan untuk kegiatan bersama seperti senam,
                            ibadah, hiburan, dan acara khusus lainnya.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.30.58_8c7c01d8 1.png') }}"
                         alt="Taman Doa">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Taman Doa</h3>
                        <p>
                            Ruang terbuka yang tenang untuk persekutuan,
                            perenungan, dan ibadah padang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.41.42_88b1ad76 1.png') }}"
                         alt="Balai Pengobatan">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Balai Pengobatan</h3>
                        <p>
                            Menyediakan layanan kesehatan dasar
                            dengan tenaga dan peralatan pendukung.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.03.54_76f5605d 1.png') }}"
                         alt="Taman">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Taman</h3>
                        <p>
                            Area hijau untuk bersantai, menikmati udara segar,
                            dan mempererat kebersamaan para lansia.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.03.58_93f2fd4f 1.png') }}"
                         alt="Dapur">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Dapur</h3>
                        <p>
                            Tempat penyajian makanan bergizi
                            yang disiapkan dengan penuh kasih.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row facility-item align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('assets/images/WhatsApp Image 2025-05-26 at 15.49.27_e0052c7a 1.png') }}"
                         alt="Tempat Laundry">
                </div>
                <div class="col-lg-6">
                    <div class="facility-text">
                        <h3>Tempat Laundry</h3>
                        <p>
                            Menjaga kebersihan pakaian lansia
                            agar tetap sehat dan nyaman setiap hari.
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
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" @click="confirmLogout">
                        Ya, Keluar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/fasilitas.js') }}"></script>
@endpush
