{{-- ==========================================
     2. lupa-password-admin.blade.php
     ========================================== --}}
@extends('layouts.admin-auth')

@section('title', 'Lupa Password')

@section('content')
<div id="lupaApp" class="container-fluid p-0 d-flex h-100">
    <div class="split-left">
        <img src="{{ asset('assets/images/loginadmin.png') }}" alt="Illustration">
    </div>

    <div class="split-right">
        <div class="auth-box">
            <h2 class="auth-title auth-title-small">Lupa Password?</h2>
            <p class="auth-subtitle">Masukkan email yang terdaftar, kami akan mengirimkan link untuk reset password.</p>
            
            <form @submit.prevent="kirimLink">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control form-control-auth" v-model="email" placeholder="Email Terdaftar" required>
                </div>

                <button type="submit" class="btn btn-white" :disabled="isLoading">
                    @{{ isLoading ? 'Mengirim...' : 'Kirim Link Reset' }}
                </button>
                
                <br>
                <a href="{{ route('admin.login') }}" class="link-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/lupa-password-admin.js') }}"></script>
@endpush