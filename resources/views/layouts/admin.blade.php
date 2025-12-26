<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Panti Wredha BDK</title>
    
    {{-- Hide Vue elements until mounted --}}
    <style>[v-cloak] { display: none !important; }</style>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    {{-- Main Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-admin.css') }}">
    
    {{-- Additional Page CSS --}}
    @stack('styles')
    
    {{-- Vue 3 --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-admin">

    <div id="adminApp" v-cloak>
        
        {{-- Loading Overlay --}}
        <div v-if="isLoading" class="loading-overlay">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        {{-- Header --}}
        @include('partials.header-admin')

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Main Content --}}
        <main class="main-content">
            <div class="content-body">
                @yield('content')
            </div>
        </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page Scripts --}}
    @stack('scripts')

</body>
</html>