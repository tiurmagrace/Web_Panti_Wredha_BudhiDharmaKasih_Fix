<footer class="footer-new" id="footerApp">
    <div class="container">
        <div class="row">

            <!-- BRAND + SEARCH -->
            <div class="col-lg-4 col-md-6 footer-brand-desc">
                <h2>Panti Wredha BDK</h2>
                <p class="footer-text">
                    "Kasih Kristus Untuk Semua" Melayani dan mendampingi para lansia menjalani hari tua dengan penuh makna.
                </p>

                <!-- 🔍 SEARCH (NYAMBUNG KE HOMEPAGE VUE) -->
                <div class="footer-search">
                    <form @submit.prevent="performSearch">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari informasi di halaman ini..."
                            autocomplete="off"
                        >
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- NAVIGASI -->
            <div class="col-lg-2 col-md-6 footer-col-mobile">
                <h5 class="footer-heading">NAVIGASI</h5>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('donatur/sejarah') }}">Profil Kami</a></li>
                    <li><a href="{{ url('donatur/donasi') }}">Donasi</a></li>
                    <li><a href="{{ url('donatur/kontak') }}">Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- SOSIAL -->
            <div class="col-lg-3 col-md-6 footer-col-mobile">
                <h5 class="footer-heading">IKUTI KAMI</h5>
                <div class="footer-social-icons mt-3">
                    <a href="https://www.tiktok.com/@pantiwredabdk510" target="_blank">
                        <img src="{{ asset('assets/images/tiktok.png') }}">
                    </a>
                    <a href="https://youtube.com/@pantiwredhabudidharmakasih8513" target="_blank">
                        <img src="{{ asset('assets/images/yt.png') }}">
                    </a>
                    <a href="https://www.facebook.com/pantiwredha.bdk" target="_blank">
                        <img src="{{ asset('assets/images/facebook.png') }}">
                    </a>
                </div>
            </div>

            <!-- JAM KERJA -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">JAM KERJA</h5>
                <ul class="footer-hours list-unstyled">
                    <li><span>Senin - Jumat</span> <span>08.00 - 16.00</span></li>
                    <li><span>Sabtu</span> <span>08.00 - 13.00</span></li>
                    <li class="text-warning"><span>Minggu</span> <span>Tutup</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-contact-bar">
            <div class="row">
                <div class="col-md-4 contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <span>Lokasi Kami</span>
                        <h6>Jl. May. Jend. Soengkono 510, Purbalingga</h6>
                    </div>
                </div>
                <div class="col-md-4 contact-item">
                    <i class="fas fa-phone-volume"></i>
                    <div>
                        <span>Hubungi Kami</span>
                        <h6>(0281) 891-829</h6>
                    </div>
                </div>
                <div class="col-md-4 contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <span>Email</span>
                        <h6>info@pantiwredhabdk.com</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            © 2025 Yayasan Panti Wredha Budi Dharma Kasih
        </div>
    </div>
</footer>
