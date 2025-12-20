{{-- ==========================================
     3. reset-password-admin.blade.php
     ========================================== --}}
@extends('layouts.admin-auth')

@section('title', 'Reset Password')

@section('content')
<div id="resetApp" class="container-fluid p-0 d-flex h-100">
    <div class="split-left">
        <img src="{{ asset('assets/images/loginadmin.png') }}" alt="Illustration">
    </div>

    <div class="split-right">
        <div class="auth-box">
            <h2 class="auth-title auth-title-small">Password Baru</h2>
            <p class="auth-subtitle">Silakan buat password baru untuk akunmu.</p>
            
            <form @submit.prevent="resetPass">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input :type="showPass1 ? 'text' : 'password'" class="form-control form-control-auth" v-model="pass1" placeholder="Password Baru" required style="border-right: none;">
                    <span class="input-group-text" style="border-left: none; border-right: 1px solid white; cursor: pointer;" @click="showPass1 = !showPass1">
                        <i class="fas" :class="showPass1 ? 'fa-eye' : 'fa-eye-slash'"></i>
                    </span>
                </div>

                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input :type="showPass2 ? 'text' : 'password'" class="form-control form-control-auth" v-model="pass2" placeholder="Konfirmasi Password" required style="border-right: none;">
                    <span class="input-group-text" style="border-left: none; border-right: 1px solid white; cursor: pointer;" @click="showPass2 = !showPass2">
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
@endsection

@push('scripts')
<script src="{{ asset('assets/js/reset-password-admin.js') }}"></script>
@endpush