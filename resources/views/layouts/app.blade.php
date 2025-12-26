<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panti Wredha Budi Dharma Kasih')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Global CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-donatur.css') }}">

    {{-- Extra CSS --}}
    @stack('styles')

    {{-- Vue --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<body>

    {{-- NAVBAR --}}
    <div id="navbarApp">
        @include('partials.navbar-donatur')
    </div>

    {{-- 🔥 SATU ROOT VUE UNTUK SEMUA --}}
    <div id="homepageApp">

        <main class="content-wrapper">
            @yield('content')
        </main>

        {{-- FOOTER (MASUK KE VUE) --}}
        @include('partials.footer-donatur')

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 🔒 PROTECTED LINK --}}
    <script>
        function handleProtectedLink(e, tujuan) {
            const isLoggedIn = localStorage.getItem('isLoggedIn');

            if (isLoggedIn !== 'true') {
                e.preventDefault();
                localStorage.setItem('redirect_after_login', tujuan);

                Swal.fire({
                    icon: 'info',
                    title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu'
                }).then(() => {
                    window.location.href = '/auth/login';
                });

                return false;
            }

            return true;
        }
    </script>

    {{-- NAVBAR VUE --}}
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    {{-- FOOTER VUE --}}
    <script src="{{ asset('assets/js/footer.js') }}"></script>
    {{-- PAGE SCRIPT --}}
    @stack('scripts')

</body>
</html>