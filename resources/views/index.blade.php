@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div id="homepageApp">

    <!-- HERO CAROUSEL -->
            <section id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>

                <div class="carousel-inner hero-carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('assets/images/FotoKegiatan1.jpg') }}" class="d-block w-100 hero-slide-img" alt="Slide Kegiatan 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/FotoKegiatan2.jpg') }}" class="d-block w-100 hero-slide-img" alt="Slide Kegiatan 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('assets/images/FotoKegiatan3.jpg') }}" class="d-block w-100 hero-slide-img" alt="Slide Kegiatan 3">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </section>

            <!-- MAIN HEADER -->
            <section class="main-header text-center">
                <div class="container">
                    <div class="header-text">
                        <h1>Yayasan Panti Wredha</h1>
                        <h2>"Budi Dharma Kasih" Purbalingga</h2>
                    </div>
                    <p class="subtitle">Merawat dengan Hati, Melayani dengan Kasih</p>
                </div>
            </section>

            <!-- ABOUT SECTION 1 -->
            <section class="about pb-5">
                <div class="container">
                    <div class="info-box glass-effect">
                        <p class="about-quote">
                            "Kasih Kristus Untuk Semua" menjadi semangat kami dalam melayani dan mendampingi para lanjut usia menjalani hari
                            tua yang penuh makna, kedamaian, dan kebahagiaan. Panti Wredha Budi Dharma Kasih Purbalingga hadir sebagai rumah
                            kedua bagi para lansia—tempat di mana mereka diterima, dihargai, dan dilayani dengan penuh cinta kasih. Kami
                            percaya bahwa masa tua bukanlah akhir dari segalanya, melainkan waktu yang berharga untuk tetap hidup sehat,
                            aktif, dan penuh sukacita. Melalui pendekatan pelayanan yang menyeluruh—mencakup kesehatan, rohani, dan
                            rekreasi—kami menciptakan lingkungan yang aman, nyaman, dan membangun, sesuai dengan visi kami dalam mewujudkan
                            kehidupan yang berkualitas bagi para lansia.
                        </p>
                    </div>
                </div>
            </section>

            <!-- GALLERY SECTION -->
            <section class="gallery-section py-4">
                <div class="container">
                    <h2 class="mb-5">DOKUMENTASI KEGIATAN KAMI</h2>
                    
                    <div class="row g-4 gallery-grid-bootstrap">
                        <div class="col-lg-4 col-md-6 gallery-item-vue" v-for="(img, index) in paginatedImages" :key="index">
                            <a href="#" data-bs-toggle="modal" :data-bs-target="'#popup-' + index">
                                <img :src="img.src" :alt="img.alt" class="gallery-image">
                            </a>
                            
                            <div class="modal fade gallery-modal" :id="'popup-' + index" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <button class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                                            <img :src="img.src" :alt="img.alt">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <nav class="mt-5" v-if="totalPages > 1">
                        <ul class="pagination justify-content-center">
                            
                            <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: currentPage === page }">
                                <a class="page-link page-num" href="javascript:void(0)" @click="setPage(page)">@{{ page }}</a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)" @click="nextPage" aria-label="Next">
                                    <span aria-hidden="true">NEXT &raquo;</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </section>

            <!-- Gallery Modals -->
            <div class="modal fade gallery-modal" id="popup-1" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/7.png') }}" alt="Dokumentasi 1">
                    </div></div>
                </div>
            </div>
            <div class="modal fade gallery-modal" id="popup-2" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/8.png') }}" alt="Dokumentasi 2">
                    </div></div>
                </div>
            </div>
            <div class="modal fade gallery-modal" id="popup-3" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/9.png') }}" alt="Dokumentasi 3">
                    </div></div>
                </div>
            </div>
            <div class="modal fade gallery-modal" id="popup-4" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/7.png') }}" alt="Dokumentasi 4">
                    </div></div>
                </div>
            </div>
            <div class="modal fade gallery-modal" id="popup-5" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/8.png') }}" alt="Dokumentasi 5">
                    </div></div>
                </div>
            </div>
            <div class="modal fade gallery-modal" id="popup-6" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content"><div class="modal-body">
                        <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                        <img src="{{ asset('assets/images/9.png') }}" alt="Dokumentasi 6">
                    </div></div>
                </div>
            </div>
            
            <!-- ABOUT SECTION 2 -->
            <section class="about py-5">
                <div class="container">
                    <div class="info-box info-box-berlogo">
                        <p class="about-quote"> "Dengan Kasih Kristus Sebagai Dasar Pelayanan Kami mendorong keluarga, masyarakat, dan
                            semua pihak untuk bersama-sama menciptakan masa tua yang penuh cinta, sejahtera, dan bermartabat bagi para lansia.
                            Panti Wredha Budi Dharma Kasih bukan hanya tempat tinggal—ini adalah rumah kami."
                        </p>
                    </div>
                </div>
            </section>

            <!-- PAVILIUN SECTION -->
            <section class="paviliun-section py-5">
                <div class="container">
                    <div class="paviliun-header text-center mb-5">
                        <h2 class="sub-title">FASILITAS & PAVILIUN KAMI</h2>
                    </div>

                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav1.jpg') }}" class="card-img-top" alt="Paviliun Bougenville 1">
                                <p>PAVILIUN BOUGENVILLE 1</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav4.png') }}" class="card-img-top" alt="Paviliun Bougenville 2">
                                <p>PAVILIUN BOUGENVILLE 2</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav3.png') }}" class="card-img-top" alt="Paviliun Bougenville 3">
                                <p>PAVILIUN BOUGENVILLE 3</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav4.png') }}" class="card-img-top" alt="Paviliun Bougenville 4">
                                <p>PAVILIUN BOUGENVILLE 4</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav1.jpg') }}" class="card-img-top" alt="Paviliun Bougenville 5">
                                <p>PAVILIUN BOUGENVILLE 5</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="paviliun-item">
                                <img src="{{ asset('assets/images/pav3.png') }}" class="card-img-top" alt="Paviliun Bougenville 6">
                                <p>PAVILIUN BOUGENVILLE 6</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA BANNER -->
            <section class="cta-banner py-5">
                <div class="container">
                    <div class="cta-content text-center">
                        <a href="{{ url('donatur/donasi') }}" class="cta-button hero-cta">
                            <img src="{{ asset('assets/images/cta_donasi.png') }}" alt="Donate Icon" style="max-width: 350px;">
                        </a>
                        <p class="cta-message mt-4">
                            Untuk informasi lebih lanjut, silahkan menghubungi kami:
                        </p>
                        <div class="contact-info">
                            <a href="tel:0281891829"><i class="fas fa-phone"></i> (0281) 891-829</a>
                            <a href="https://wa.me/6281394661664" target="_blank"><i class="fab fa-whatsapp"></i> whatsapp 0813-9466-1664</a>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- LOGOUT MODAL -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold text-danger">Konfirmasi Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
                        <p class="mb-0 fw-medium fs-5">Apakah Anda yakin ingin keluar dari akun?</p>
                        <p class="text-muted small">Anda harus login kembali untuk mengakses fitur donasi.</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pb-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger rounded-pill px-4" @click="confirmLogout">Ya, Keluar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/homepage-vue.js') }}"></script>
@endpush
