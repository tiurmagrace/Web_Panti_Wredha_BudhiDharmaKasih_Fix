@extends('layouts.auth')

@section('title', 'Password Baru')

@section('content')
<div id="resetApp" v-cloak class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">

            <h2>New Password</h2>

            <p class="mb-4"
               style="text-align:justify;font-size:.85rem;color:#333;font-weight:500;">
                Masukkan kata sandi baru dengan minimal 8 karakter.
            </p>

            <form @submit.prevent="resetPassword">

                <div v-if="errorMsg"
                     class="alert alert-danger py-1 small">
                    @{{ errorMsg }}
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Password Baru</label>
                    <div class="password-wrapper">
                        <input :type="showPass1 ? 'text' : 'password'"
                               class="form-control-figma"
                               v-model="pass1"
                               required
                               minlength="8">

                        <i class="fa-regular toggle-password-icon"
                           :class="showPass1 ? 'fa-eye' : 'fa-eye-slash'"
                           @click="showPass1 = !showPass1">
                        </i>
                    </div>
                </div>

                <div class="figma-input-group">
                    <label class="figma-label">Konfirmasi Password</label>
                    <div class="password-wrapper">
                        <input :type="showPass2 ? 'text' : 'password'"
                               class="form-control-figma"
                               v-model="pass2"
                               required
                               minlength="8">

                        <i class="fa-regular toggle-password-icon"
                           :class="showPass2 ? 'fa-eye' : 'fa-eye-slash'"
                           @click="showPass2 = !showPass2">
                        </i>
                    </div>
                </div>

                <button type="submit"
                        class="btn-figma-outline"
                        :disabled="isLoading">
                    @{{ isLoading ? 'MEMPROSES...' : 'RESET PASSWORD' }}
                </button>

            </form>
        </div>
    </div>

    {{-- IMAGE SIDE --}}
    <div class="auth-image-side login-mode"></div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/reset-password-baru-donatur.js') }}"></script>
@endpush
