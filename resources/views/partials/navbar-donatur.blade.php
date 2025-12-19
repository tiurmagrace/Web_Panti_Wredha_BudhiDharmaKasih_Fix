<nav class="navbar navbar-expand-lg sticky-top" data-bs-theme="dark">
    <div class="container-fluid px-4">

        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('assets/images/1.png') }}" alt="Logo Panti Wredha">
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">BERANDA</a>
                </li>

                {{-- PROFIL (PUBLIK) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        PROFIL
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('donatur/sejarah') }}">Sejarah Singkat</a></li>
                        <li><a class="dropdown-item" href="{{ url('donatur/visi-misi') }}">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="{{ url('donatur/fasilitas') }}">Fasilitas</a></li>
                        <li><a class="dropdown-item" href="{{ url('donatur/persyaratan') }}">Persyaratan</a></li>
                    </ul>
                </li>

                {{-- DONASI (WAJIB PUBLIK, TANPA JS) --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('donatur/donasi') }}">
                        DONASI
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('donatur/kontak') }}">KONTAK</a>
                </li>

            </ul>
        </div>

        <div class="nav-actions d-flex align-items-center gap-3">

            {{-- NOTIFIKASI (PROTECTED) --}}
            <a href="{{ url('donatur/notifikasi') }}"
               onclick="return handleProtectedLink(event, '{{ url('donatur/notifikasi') }}')">
                <img src="{{ asset('assets/images/3.png') }}" alt="Notifikasi">
            </a>

            {{-- LOGIN STATE --}}
            <div v-if="isLoggedIn" class="d-flex align-items-center gap-2">
                <span class="text-white fw-bold">
                    Hi, @{{ currentUser.username }}
                </span>
                <button class="btn btn-sm btn-danger rounded-pill px-3"
                        @click="confirmLogout">
                    Logout
                </button>
            </div>

            <a v-else href="{{ url('auth/login') }}" class="cta-button primary">
                LOGIN
            </a>

        </div>

    </div>
</nav>
