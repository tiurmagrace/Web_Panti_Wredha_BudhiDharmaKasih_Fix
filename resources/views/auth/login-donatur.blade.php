@extends('layouts.auth')

@section('title', 'Login Donatur')

@section('content')
<div id="loginApp" v-cloak class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">
            <h2>LOGIN</h2>

        @if(session('login_required'))
        <script>
        Swal.fire({
            icon: 'warning',
            title: 'Harus Login',
            text: 'Silakan login terlebih dahulu untuk mengakses halaman ini.'
        })
        </script>
        @endif



            <form @submit.prevent="handleLogin">

                <div v-if="errorMessage"
                     class="alert alert-danger py-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    @{{ errorMessage }}
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Email</label>
                    <input type="email"
                           class="form-control-figma"
                           v-model="email"
                           required
                           placeholder="Masukkan email terdaftar">
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Password</label>
                    <div class="password-wrapper">
                        <input :type="showPassword ? 'text' : 'password'"
                               class="form-control-figma"
                               v-model="password"
                               required
                               placeholder="Masukkan password">

                        <i class="fa-regular toggle-password-icon"
                           :class="showPassword ? 'fa-eye' : 'fa-eye-slash'"
                           @click="showPassword = !showPassword">
                        </i>
                    </div>

                    <div class="text-end mt-2">
                        <a href="{{ url('/auth/lupa-password') }}"
                           class="forgot-password-link">
                            Lupa password?
                        </a>
                    </div>
                </div>

                <button type="submit"
                        class="btn-figma-outline"
                        :disabled="isLoading">
                    <span v-if="isLoading">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </span>
                    <span v-else>LOGIN</span>
                </button>

                <div class="text-center-link">
                    Belum punya akun?
                    <a href="{{ url('/auth/signup') }}">SIGN UP</a>
                </div>

            </form>
        </div>
    </div>

    {{-- IMAGE SIDE --}}
    <div class="auth-image-side login-mode"></div>

</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/login-donatur.js') }}"></script>
@endpush
