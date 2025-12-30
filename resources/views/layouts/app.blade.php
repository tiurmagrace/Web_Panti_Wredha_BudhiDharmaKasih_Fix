<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panti Wredha Budi Dharma Kasih')</title>

    {{-- Preconnect untuk speed up --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Critical CSS inline untuk mencegah blank/flash --}}
    <style>
        /* Hide Vue elements until mounted - CRITICAL */
        [v-cloak] { display: none !important; }
        
        /* Immediate render styles */
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            background-color: #EAF4FF;
            opacity: 1;
            visibility: visible;
        }
        
        /* Navbar immediate render */
        .navbar {
            background-color: #1D4E89;
            padding: 5px 0;
            min-height: 80px;
        }
        
        /* Main content wrapper */
        #homepageApp {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: calc(100vh - 80px);
            opacity: 1;
        }
        
        main.content-wrapper {
            flex: 1 0 auto;
            background-color: #EAF4FF;
            min-height: 400px;
        }
        
        /* Footer immediate render */
        .footer-new {
            background-color: #1D4E89;
            flex-shrink: 0;
            margin-top: auto;
            min-height: 200px;
        }
        
        /* Prevent layout shift */
        .nav-link, .dropdown-menu { visibility: visible; }
        
        /* Smooth transition after load */
        .page-ready main.content-wrapper {
            animation: fadeInContent 0.2s ease-out;
        }
        
        @keyframes fadeInContent {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }
    </style>

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
</head>

<body>

    {{-- NAVBAR - Standalone Vue App --}}
    <div id="navbarApp">
        @include('partials.navbar-donatur')
    </div>

    {{-- MAIN CONTENT WRAPPER --}}
    <div id="homepageApp">
        <main class="content-wrapper">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        @include('partials.footer-donatur')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Vue Production --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 🔒 PROTECTED LINK HANDLER --}}
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
        
        // Mark page as ready after DOM loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('page-ready');
        });
    </script>

    {{-- NAVBAR VUE --}}
    <script src="{{ asset('assets/js/navbar.js') }}?v={{ time() }}"></script>
    {{-- FOOTER VUE --}}
    <script src="{{ asset('assets/js/footer.js') }}?v={{ time() }}"></script>
    {{-- PAGE SCRIPT --}}
    @stack('scripts')

</body>
</html>