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
                        <h3>Kamar tidur</h3>
                        <p>Setiap kamar tidur dilengkapi dengan fasilitas yang menunjang kenyamanan penghuni, seperti kasur yang bersih dan empuk, meja dan kursi untuk beraktivitas ringan, kipas angin untuk menjaga sirkulasi udara tetap sejuk, serta pispot sebagai alat bantu buang air yang memudahkan lansia dalam beraktivitas sehari-hari.</p>
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
                        <p>Paviliun pria dirancang khusus untuk menciptakan lingkungan yang tenang dan nyaman bagi penghuni lansia laki-laki. Setiap ruang diatur agar bersih, rapi, serta mendukung privasi dan kenyamanan dalam beristirahat maupun beraktivitas sehari-hari.</p>
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
                        <h3>Pavilion Sneek Khusus Wanita</h3>
                        <p>Paviliun wanita menghadirkan suasana yang hangat dan tertata dengan baik, memberikan kenyamanan dan ketenangan bagi para lansia perempuan. Fasilitas di dalamnya disesuaikan untuk mendukung aktivitas harian dengan tetap menjaga privasi dan keamanan.</p>
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
                        <h3>Pavilion Temanggung Khusus Pasangan</h3>
                        <p>Paviliun suami istri dirancang untuk memberikan tempat tinggal yang layak dan nyaman bagi pasangan lansia. Dengan penataan ruang yang efisien dan fasilitas yang mendukung kebutuhan bersama, paviliun ini memastikan privasi, keamanan, dan kenyamanan tetap terjaga. sehingga pasangan dapat menjalani hari-hari mereka dengan damai dan tenteram.</p>
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
                        <p>Ruang aula berfungsi sebagai tempat berkumpul untuk berbagai kegiatan bersama, seperti senam lansia, ibadah, hiburan, atau acara khusus lainnya. Ruangan ini luas, sejuk, dan nyaman, mendukung interaksi sosial yang sehat di antara para penghuni.</p>
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
                        <p>Taman Doa dihadirkan sebagai ruang terbuka yang tenang dan teduh, menjadi tempat persekutuan dan perenungan firman Tuhan di tengah keindahan alam. Suasana alam yang asri mendukung ibadah padang yang khusyuk, menghadirkan kedamaian dan keintiman bersama Sang Pencipta.</p>
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
                        <p>Balai pengobatan tersedia untuk memberikan layanan kesehatan dasar bagi seluruh penghuni. Dilengkapi dengan peralatan medis sederhana dan tenaga kesehatan yang siap membantu dalam pemeriksaan rutin maupun penanganan keluhan ringan.</p>
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
                        <p>Taman yang rindang di panti jompo menjadi tempat beristirahat dan bersantal bagi para lansia. Dengan pepohonan yang teduh dan udara segar, taman ini menghadirkan ketenangan, mempererat kebersamaan, dan memberi ruang untuk menikmati hari dengan damai.</p>
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
                        <p>Dapur menjadi jantung yang hangat di panti, tempat di mana hidangan bergizi disiapkan dengan penuh kasih. Setiap aroma yang tercium dan rasa yang tersaji menjadi bagian dari perhatian dan kepedulian bagi para lansia, memastikan kebutuhan mereka terpenuhi dengan baik, aman, dan penuh cinta.</p>
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
                        <p>Tempat laundry di panti berperan penting dalam menjaga kebersihan dan kenyamanan para lansia. Dengan proses yang tertata rapi, pakaian dicuci dan dirawat dengan teliti, memastikan setiap penghuni tetap merasa segar, bersih, dan terjaga kesehatannya setiap hari.</p>
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
