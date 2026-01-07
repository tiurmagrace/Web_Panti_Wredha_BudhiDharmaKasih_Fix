@extends('layouts.admin-auth')

@section('title', 'Lupa Password')

@section('content')
<div id="lupaApp" class="container-fluid p-0 d-flex h-100">
    <div class="split-left">
        <img src="{{ asset('assets/images/loginadmin.png') }}" alt="Illustration">
    </div>

    <div class="split-right">
        <div class="auth-box">
            <!-- Step 1: Input Email -->
            <div v-if="step === 1">
                <h2 class="auth-title auth-title-small">Lupa Password?</h2>
                <p class="auth-subtitle">Masukkan email yang terdaftar, kami akan mengirimkan kode verifikasi.</p>
                
                <form @submit.prevent="kirimKode">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control form-control-auth" v-model="email" placeholder="Email Terdaftar" required>
                    </div>

                    <button type="submit" class="btn btn-white" :disabled="isLoading">
                        @{{ isLoading ? 'Mengirim...' : 'Kirim Kode Verifikasi' }}
                    </button>
                    
                    <br>
                    <a href="{{ route('admin.login') }}" class="link-back">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Login
                    </a>
                </form>
            </div>

            <!-- Step 2: Input Kode Verifikasi -->
            <div v-if="step === 2">
                <h2 class="auth-title auth-title-small">Masukkan Kode</h2>
                <p class="auth-subtitle">Kode verifikasi telah dikirim ke <strong>@{{ email }}</strong></p>
                
                <form @submit.prevent="verifikasiKode">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="text" class="form-control form-control-auth" v-model="code" placeholder="Masukkan 6 digit kode" maxlength="6" required style="letter-spacing: 5px; text-align: center; font-size: 1.2rem;">
                    </div>

                    <button type="submit" class="btn btn-white" :disabled="isLoading">
                        @{{ isLoading ? 'Memverifikasi...' : 'Verifikasi Kode' }}
                    </button>
                    
                    <br>
                    <a href="#" @click.prevent="step = 1" class="link-back">
                        <i class="fas fa-arrow-left me-2"></i>Ganti Email
                    </a>
                </form>
            </div>

            <!-- Step 3: Input Password Baru -->
            <div v-if="step === 3">
                <h2 class="auth-title auth-title-small">Password Baru</h2>
                <p class="auth-subtitle">Silakan buat password baru untuk akunmu.</p>
                
                <form @submit.prevent="resetPassword">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input :type="showPass1 ? 'text' : 'password'" class="form-control form-control-auth" v-model="password" placeholder="Password Baru (min 6 karakter)" required minlength="6" style="border-right: none;">
                        <span class="input-group-text" style="border-left: none; cursor: pointer;" @click="showPass1 = !showPass1">
                            <i class="fas" :class="showPass1 ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </span>
                    </div>

                    <div class="input-group mb-4">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input :type="showPass2 ? 'text' : 'password'" class="form-control form-control-auth" v-model="passwordConfirm" placeholder="Konfirmasi Password" required minlength="6" style="border-right: none;">
                        <span class="input-group-text" style="border-left: none; cursor: pointer;" @click="showPass2 = !showPass2">
                            <i class="fas" :class="showPass2 ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-white" :disabled="isLoading">
                        @{{ isLoading ? 'Menyimpan...' : 'Simpan Password' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/lupa-password-admin.js') }}"></script>
@endpush
