@extends('layouts.auth')

@section('title', 'Verifikasi Kode')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/auth-otp.css') }}">
@endpush

@section('content')
<div id="verifyApp" v-cloak class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">

            <h2 style="font-size:1.5rem;">Enter Confirmation Code</h2>

            <p class="text-center"
               style="font-size:0.9rem;margin-bottom:2rem;color:#333;">
                Lihat Gmail Anda untuk melihat kode verifikasi
            </p>

            <form @submit.prevent="submitCode">

                <div class="otp-input-group">
                    <input
                        v-for="(n, index) in 4"
                        :key="index"
                        type="text"
                        class="otp-box"
                        maxlength="1"
                        @input="handleInput($event, index)"
                        ref="otpInputs"
                        required
                    >
                </div>

                <button type="submit" class="btn-figma-outline">
                    SELANJUTNYA
                </button>

            </form>

        </div>
    </div>

    {{-- IMAGE SIDE --}}
    <div class="auth-image-side login-mode"></div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/verifikasi-kode-donatur.js') }}"></script>
@endpush
