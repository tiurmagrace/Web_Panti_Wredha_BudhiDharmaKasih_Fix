@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div id="forgotApp" v-cloak class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma">

            <!-- Step 1: Input Email -->
            <div v-if="step === 1">
                <h2>Reset Password</h2>
                <p class="text-center" style="font-size:0.9rem;margin-bottom:2rem;color:#333;">
                    Lupa Password Anda? Masukkan email Anda dan kode verifikasi akan dikirimkan.
                </p>

                <form @submit.prevent="kirimKode">
                    <div class="figma-input-group">
                        <label class="figma-label">Email</label>
                        <input type="email" class="form-control-figma" v-model="email" required placeholder="contoh@email.com">
                    </div>

                    <button type="submit" class="btn-figma-outline" :disabled="isLoading">
                        @{{ isLoading ? 'MENGIRIM...' : 'KIRIM KODE' }}
                    </button>

                    <div class="text-center-link">
                        <a href="{{ url('/auth/login') }}">
                            <i class="fas fa-arrow-left"></i> Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Step 2: Input Kode Verifikasi -->
            <div v-if="step === 2">
                <h2>Masukkan Kode</h2>
                <p class="text-center" style="font-size:0.9rem;margin-bottom:2rem;color:#333;">
                    Kode verifikasi telah dikirim ke <strong>@{{ email }}</strong>
                </p>

                <form @submit.prevent="verifikasiKode">
                    <div class="figma-input-group">
                        <label class="figma-label">Kode Verifikasi</label>
                        <input type="text" class="form-control-figma" v-model="code" required placeholder="Masukkan 6 digit kode" maxlength="6" style="letter-spacing: 5px; text-align: center; font-size: 1.2rem;">
                    </div>

                    <button type="submit" class="btn-figma-outline" :disabled="isLoading">
                        @{{ isLoading ? 'MEMVERIFIKASI...' : 'VERIFIKASI' }}
                    </button>

                    <div class="text-center-link">
                        <a href="#" @click.prevent="step = 1">
                            <i class="fas fa-arrow-left"></i> Ganti Email
                        </a>
                    </div>
                </form>
            </div>

            <!-- Step 3: Input Password Baru -->
            <div v-if="step === 3">
                <h2>Password Baru</h2>
                <p class="text-center" style="font-size:0.9rem;margin-bottom:2rem;color:#333;">
                    Masukkan password baru dengan minimal 6 karakter.
                </p>

                <form @submit.prevent="resetPassword">
                    <div class="figma-input-group">
                        <label class="figma-label">Password Baru</label>
                        <div class="password-wrapper">
                            <input :type="showPass1 ? 'text' : 'password'" class="form-control-figma" v-model="password" required minlength="6">
                            <i class="fa-regular toggle-password-icon" :class="showPass1 ? 'fa-eye' : 'fa-eye-slash'" @click="showPass1 = !showPass1"></i>
                        </div>
                    </div>

                    <div class="figma-input-group">
                        <label class="figma-label">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input :type="showPass2 ? 'text' : 'password'" class="form-control-figma" v-model="passwordConfirm" required minlength="6">
                            <i class="fa-regular toggle-password-icon" :class="showPass2 ? 'fa-eye' : 'fa-eye-slash'" @click="showPass2 = !showPass2"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-figma-outline" :disabled="isLoading">
                        @{{ isLoading ? 'MEMPROSES...' : 'RESET PASSWORD' }}
                    </button>
                </form>
            </div>

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
