@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<div id="kontakApp" v-cloak>

    {{-- HERO --}}
    <section class="contact-hero">
        <div class="hero-content">
            <h1>HUBUNGI KAMI</h1>
            <h3>Ingin mengetahui kami lebih baik?</h3>
            <p>Mari Terhubung, Karena Setiap Suara Berarti 😊</p>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="contact-content py-5">
        <div class="container">
            <div class="row g-5">

                {{-- INFO --}}
                <div class="col-lg-5">

                    <div class="contact-info-block">
                        <div class="info-icon-circle">
                            <i class="fas fa-location-dot"></i>
                        </div>
                        <h4>Kunjungi kami</h4>
                        <p>Jl. Raya Mayjen Sungkono No.510</p>
                        <p>Kalimanah Wetan, Purbalingga</p>
                        <div class="info-divider"></div>
                    </div>

                    <div class="contact-info-block">
                        <div class="info-icon-circle">
                            <i class="fas fa-phone-volume"></i>
                        </div>
                        <h4>Hubungi kami</h4>
                        <p>(0281) 891 829</p>
                        <p>+62 813-9466-1664</p>
                        <div class="info-divider"></div>
                    </div>

                    <div class="contact-info-block">
                        <div class="info-icon-circle">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>Media sosial kami</h4>
                        <div class="contact-socials d-flex flex-column align-items-center gap-3">
                            <a href="#"><i class="fab fa-instagram"></i> <span>@pantiwredhabudidharmakasih</span></a>
                            <a href="#"><i class="fab fa-facebook"></i> <span>Panti Wredha BDK</span></a>
                            <a href="#"><i class="fab fa-youtube"></i> <span>Panti Wredha Budi Dharma Kasih</span></a>
                        </div>
                    </div>

                </div>

                {{-- FORM --}}
                <div class="col-lg-7">
                    <div class="ps-lg-5">
                        <h5 class="contact-form-header">
                            Setiap pesan Anda sangat berarti.
                            Mari bersama-sama menciptakan lingkungan penuh kasih bagi para lansia!
                            <hr style="width:100%;margin-top:20px;opacity:.2;">
                        </h5>

                        <form @submit.prevent="kirimPesan" class="form-contact">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text"
                                       class="form-control"
                                       v-model="form.nama"
                                       placeholder="Nama Anda"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       class="form-control"
                                       v-model="form.email"
                                       placeholder="email@anda.com"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="tel"
                                       class="form-control"
                                       v-model="form.telepon"
                                       placeholder="08xxxxxxxx">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pesan Anda</label>
                                <textarea class="form-control"
                                          rows="6"
                                          v-model="form.pesan"
                                          placeholder="Tulis pesan Anda di sini..."
                                          required></textarea>
                            </div>

                            <button type="submit" class="btn-kirim-pesan">
                                Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- MAP --}}
    <section class="contact-map pb-5">
        <div class="container">
            <div class="map-container">
                <iframe
                    src="https://maps.google.com/maps?q=Jl.+Raya+Mayjen+Sungkono+No.510,+Kalimanah+Wetan,+Purbalingga&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    width="100%"
                    height="450"
                    style="border:0;"
                    loading="lazy">
                </iframe>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/kontak.js') }}"></script>
@endpush
