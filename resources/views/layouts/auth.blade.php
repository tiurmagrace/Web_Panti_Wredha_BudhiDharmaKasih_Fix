<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth - Panti Wredha BDK')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inknut+Antiqua:wght@700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Auth CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-donatur.css') }}">

    {{-- Vue --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>

    @yield('content')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Extra Scripts --}}
    @stack('scripts')

</body>
</html>