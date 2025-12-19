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
    
    {{-- Search Highlight CSS --}}
    <style>
        /* Search Highlight Styles */
        .highlight-text {
            background: linear-gradient(120deg, #ffd700 0%, #ffed4e 100%);
            color: #000;
            font-weight: 600;
            padding: 2px 4px;
            border-radius: 3px;
            animation: highlightPulse 1.5s ease-in-out;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
        }

        @keyframes highlightPulse {
            0%, 100% {
                background-color: #ffd700;
                transform: scale(1);
            }
            50% {
                background-color: #ffed4e;
                transform: scale(1.05);
            }
        }

        /* Footer Search Enhanced */
        .footer-search {
            margin-top: 20px;
            position: relative;
        }

        .footer-search form {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 4px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .footer-search form:hover,
        .footer-search form:focus-within {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }

        .footer-search input {
            flex: 1;
            background: transparent;
            border: none;
            padding: 12px 20px;
            color: #fff;
            font-size: 14px;
            outline: none;
        }

        .footer-search input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-search button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 50px;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .footer-search button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Footer Links Interactive */
        .footer-links li {
            position: relative;
            transition: padding-left 0.3s ease;
        }

        .footer-links li::before {
            content: '›';
            position: absolute;
            left: 0;
            color: #667eea;
            font-size: 18px;
            font-weight: bold;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .footer-links li:hover {
            padding-left: 20px;
        }

        .footer-links li:hover::before {
            opacity: 1;
        }

        .footer-links a:hover {
            color: #fff;
            transform: translateX(5px);
        }

        /* Social Icons Animated */
        .footer-social-icons a {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .footer-social-icons a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px) rotate(5deg);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Contact Bar Enhanced */
        .contact-item {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .contact-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-3px);
        }

        .contact-item:hover i {
            transform: scale(1.2) rotate(5deg);
            color: #764ba2;
        }
    </style>

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

        <main>
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

    {{-- PAGE SCRIPT --}}
    @stack('scripts')

</body>
</html>