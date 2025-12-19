@extends('layouts.auth')

@section('title', 'Sign Up Donatur')

@section('content')
<div id="signupApp" v-cloak class="auth-split-container">

    {{-- IMAGE SIDE --}}
    <div class="auth-image-side signup-mode"></div>

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">
            <h2>SIGN UP</h2>

            <form @submit.prevent="handleSignup">

                <div v-if="errorMsg"
                     class="alert alert-danger py-2 fs-6">
                    @{{ errorMsg }}
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Email</label>
                    <input type="email"
                           class="form-control-figma"
                           v-model="form.email"
                           required>
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Username</label>
                    <input type="text"
                           class="form-control-figma"
                           v-model="form.username"
                           required>
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">
                        Password
                        <span class="text-muted small">(maks 12 karakter)</span>
                    </label>

                    <div class="password-wrapper">
                        <input :type="showPass ? 'text' : 'password'"
                               class="form-control-figma"
                               v-model="form.password"
                               maxlength="12"
                               required>

                        <i class="fa-regular toggle-password-icon"
                           :class="showPass ? 'fa-eye' : 'fa-eye-slash'"
                           @click="showPass = !showPass">
                        </i>
                    </div>
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Konfirmasi Password</label>
                    <input type="password"
                           class="form-control-figma"
                           v-model="form.confirmPassword"
                           required>
                </div>

                <button type="submit"
                        class="btn-figma-outline"
                        :disabled="isLoading">
                    @{{ isLoading ? 'Loading...' : 'SIGN UP' }}
                </button>

                <div class="text-center-link">
                    Sudah punya akun?
                    <a href="{{ url('/auth/login') }}">LOGIN</a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/signup-donatur.js') }}"></script>
@endpush
