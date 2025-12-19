@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div id="forgotApp" v-cloak class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">

            <h2>Reset Password</h2>

            <p class="text-center"
               style="font-size:0.9rem;margin-bottom:2rem;color:#333;">
                Lupa Password Anda?  
                Silahkan masukkan email Anda dan kode akan dikirimkan.
            </p>

            <form @submit.prevent="kirimKode">

                <div class="figma-input-group">
                    <label class="figma-label">Email</label>
                    <input type="email"
                           class="form-control-figma"
                           v-model="email"
                           required
                           placeholder="contoh@email.com">
                </div>

                <button type="submit"
                        class="btn-figma-outline"
                        :disabled="isLoading">
                    @{{ isLoading ? 'MENGIRIM...' : 'KIRIM' }}
                </button>

                <div class="text-center-link">
                    <a href="{{ url('/auth/login') }}">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
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
<script src="{{ asset('assets/js/lupa-password-donatur.js') }}"></script>
@endpush
