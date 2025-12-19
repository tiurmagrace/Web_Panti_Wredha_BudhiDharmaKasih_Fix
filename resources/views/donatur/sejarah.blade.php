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
                                    Sejarah yayasan ini dimulai pada tahun 1972 ketika Bapak Lie Hok Tjan (Budi Soedarma) memiliki visi mulia untuk mendirikan sebuah Panti Wredha. Beliau tidak hanya mencetuskan ide ini, tetapi juga menunjukkan keseriusannya dengan bersedia menghibahkan sebagian tanah miliknya yang berlokasi di Kalimanah, Purbalingga. Tanah ini dipersiapkan secara khusus untuk mewujudkan impiannya tersebut.
                                </p>
                                
                                <p>
                                    Bertahun-tahun kemudian, tepatnya pada tanggal 2 Desember 1988, sebuah langkah penting kembali terukir dalam sejarah yayasan. Pada tanggal ini, Yayasan Pelayanan Kristen 'Budi Dharma Kasih' secara resmi didirikan. Pemilihan nama 'Budi Dharma' bukanlah tanpa alasan. Nama ini dipilih sebagai bentuk penghormatan yang mendalam kepada Bapak Lie Hok Tjan, yang telah memberikan kontribusi awal dan menjadi inspirasi utama bagi terbentuknya yayasan ini. Beliau dianggap sebagai sosok yang telah membuka jalan dan memberikan landasan bagi berdirinya yayasan.
                                </p>
                                
                                <p>
                                    Tidak berselang lama, pada tanggal 24 Desember 1988, Ibu Lie Hok Tjan turut berperan aktif dalam mewujudkan cita-cita luhur sang suami. Beliau merealisasikan keinginan Bapak Lie Hok Tjan dengan menghibahkan sebagian tanah milik mereka secara khusus untuk pembangunan Panti Wredha. Tindakan ini menunjukkan komitmen keluarga dalam mewujudkan pelayanan bagi para lansia.
                                </p>
                                
                                <p>
                                    Memasuki tahun 1989, tepatnya pada bulan Februari, yayasan ini mendapatkan dukungan yang sangat berarti dari tiga wanita yang kemudian dikenal sebagai pendiri dan penyandang dana utama. Ketiga wanita tersebut adalah Ibu Ny Liek Ny Eling, dan Ny Tiang. Mereka tidak hanya memberikan dukungan finansial, tetapi juga berperan aktif dalam memikul pembangunan fisik Panti Wredha. Kerja keras dan dedikasi mereka membuahkan hasil dengan selesainya unit pertama Panti Wredha pada tanggal 23 September 1989.
                                </p>
                                
                                <p>
                                    Hingga saat ini, setelah lebih dari tiga puluh tahun sejak awal mula pendiriannya, semangat pelayanan yayasan ini terus berkobar. Pengelolaan yayasan kini berada di tangan generasi berikutnya, yaitu anak dan menantu dari para pendiri, yang dengan setia melanjutkan visi dan misi mulia yang telah diletakkan sejak awal.
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
