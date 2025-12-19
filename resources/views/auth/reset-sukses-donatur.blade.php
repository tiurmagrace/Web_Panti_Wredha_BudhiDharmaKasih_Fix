@extends('layouts.auth')

@section('title', 'Reset Password Berhasil')

@section('content')
<div class="auth-split-container">

    {{-- FORM SIDE --}}
    <div class="auth-form-side">
        <div class="auth-card-figma text-center">

            <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
                <h2 class="m-0" style="font-size: 1.5rem;">
                    Reset Password Done
                </h2>
                <i class="fas fa-check-circle text-primary" style="font-size: 2rem;"></i>
            </div>

            <p style="font-size: 0.9rem; margin-bottom: 2rem; color: #333; font-weight: 500;">
                Password anda berhasil diubah, silahkan login kembali!
            </p>

            <a href="{{ url('/auth/login') }}"
               class="btn-figma-outline text-decoration-none">
                CONTINUE
            </a>

        </div>
    </div>

    {{-- IMAGE SIDE --}}
    <div class="auth-image-side login-mode"></div>

</div>
@endsection
