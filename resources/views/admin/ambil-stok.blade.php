@extends('layouts.admin')

@section('title', 'Ambil Stok Barang')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/ambil-stok.css') }}">
@endpush

@section('content')
{{-- Vue mounting di sini (child app) --}}
<div id="ambilStokApp">
    <div class="page-title-banner" style="background-color: #21698a; color: white; padding: 15px; text-align: center; border-radius: 6px; font-weight: bold; font-size: 1.2rem; margin-bottom: 20px;">
        Form Pengambilan Stok Barang
    </div>

    <div class="form-container">
        <div class="section-header-teal" style="background-color: #21698a;">Data Pengambilan</div>

        <div class="form-group-row">
            <label class="label-custom">Pilih Barang</label>
            <input list="barangOptions" class="input-custom" v-model="selectedNamaBarang" @change="cekStok" @input="cekStok" placeholder="Ketik nama barang untuk mencari..." autocomplete="off">
            <datalist id="barangOptions">
                <option v-for="item in availableItems" :value="item.nama">
                    Sisa: @{{ item.sisa_stok }}
                </option>
            </datalist>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Stok Tersedia</label>
            <div style="flex: 1;">
                <span class="stok-info-box">@{{ stokTersediaDisplay || '-' }}</span>
            </div>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Jumlah Ambil</label>
            <input type="number" class="input-custom" v-model="form.jumlah" placeholder="0" min="1">
            <span v-if="satuanBarang" class="ms-2 fw-bold">@{{ satuanBarang }}</span>
        </div>

        <div class="form-group-row">
            <label class="label-custom">Tanggal Ambil</label>
            <input type="date" class="input-custom" v-model="form.tanggal">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Tujuan / Keperluan</label>
            <input type="text" class="input-custom" v-model="form.keperluan" placeholder="Contoh: Dapur, Kebersihan">
        </div>

        <div class="form-group-row">
            <label class="label-custom">Nama Pengambil</label>
            <input type="text" class="input-custom" v-model="form.petugas">
        </div>

        <div class="text-end mt-5">
            <button @click="submitAmbil" class="btn-submit-custom">
                <i class="fas fa-sign-out-alt me-2"></i> Keluarkan Barang
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/ambil-stok.js') }}"></script>
@endpush