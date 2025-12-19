@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div id="notifikasiApp" v-cloak class="content-wrapper py-5">

    <div class="container">
        <h2 class="text-center mb-4">NOTIFIKASI ANDA</h2>

        <div v-if="!isLoggedIn" class="text-center text-muted">
            Silakan login untuk melihat notifikasi
        </div>

        <div v-else class="card shadow-sm">
            <div class="list-group list-group-flush">

                <div class="list-group-item">
                    <strong>Donasi Berhasil Diterima</strong>
                    <p class="mb-0 small">Terima kasih atas donasi Anda</p>
                </div>

                <div class="list-group-item">
                    <strong>Jadwal Kunjungan</strong>
                    <p class="mb-0 small">Lihat jadwal kunjungan terbaru</p>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/notifikasi-donatur.js') }}"></script>
@endpush
