{{-- ==========================================
     1. login-admin.blade.php
     ========================================== --}}
@extends('layouts.admin-auth')

@section('title', 'Login Admin')

@section('content')
<div id="loginApp" class="container-fluid p-0 d-flex h-100">
    <div class="split-left">
        <img src="{{ asset('assets/images/loginadmin.png') }}" alt="Admin Illustration">
    </div>

    <div class="split-right">
        <div class="auth-box">
            <h2 class="auth-title">Admin Login</h2>
            
            <form @submit.prevent="handleLogin">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control form-control-auth" v-model="username" placeholder="Email Admin" required>
                </div>
                
                <div class="input-group mb-2">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input :type="showPassword ? 'text' : 'password'" class="form-control form-control-auth" v-model="password" placeholder="Password" required style="border-right: none;">
                    
                    <span class="input-group-text" style="border-left: none; border-right: 1px solid white; cursor: pointer;" @click="showPassword = !showPassword">
                        <i class="fas" :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"></i>
                    </span>
                </div>

                <div class="text-end w-100">
                    <a href="{{ route('admin.lupa-password') }}" class="forgot-link">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-white" :disabled="isLoading">
                    @{{ isLoading ? 'Memuat...' : 'Login Masuk' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/login-admin.js') }}"></script>
@endpush





