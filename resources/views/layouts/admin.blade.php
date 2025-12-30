<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Panti Wredha BDK</title>
    
    {{-- Critical CSS - Prevent flash/blank --}}
    <style>
        /* Hide Vue elements until mounted */
        [v-cloak] { display: none !important; }
        
        /* Immediate render styles */
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
        }
        
        .bg-admin {
            background-color: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        /* Skeleton loading for cards */
        .skeleton-card {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 8px;
            min-height: 100px;
        }
        
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    {{-- Main Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style-admin.css') }}">
    
    {{-- Additional Page CSS --}}
    @stack('styles')
    
    {{-- Vue 3 Production --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-admin">

    <div id="adminApp" v-cloak>
        
        {{-- Loading Overlay - Only show when actually loading data --}}
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