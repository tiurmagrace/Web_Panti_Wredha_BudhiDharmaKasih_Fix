@extends('layouts.app')

@section('title', 'Donasi')

@section('content')
<div id="donasiPilihanApp" v-cloak>

    {{-- HERO IMAGE --}}
    <section class="text-center p-0">
        <img src="{{ asset('assets/images/bakti sosial.png') }}"
             alt="Bakti Sosial Panti Wredha"
             class="hero-banner-full">
    </section>

    {{-- KONTEN DONASI --}}
    <section class="pb-5 pt-4" style="background-color: var(--color-light-bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="donasi-direct-content">

                        <p>
                            Apabila Ibu/Bapak/Saudara/i tergerak untuk memberikan bantuan dan dukungan,
                            silahkan menghubungi kami (0281) 891-829, Whatsapp: 0813-9466-1664 atau email
                            Panti “Budi Dharma Kasih” Purbalingga@gmail.com
                        </p>

                        <h3>Ungkapan Kasih / Donasi dapat ditransfer ke rekening:</h3>

                        <ul>
                            <li>BCA KCP Pondok Indah, No. rekening xxxxxxxxxxx atas nama Yayasan BDK</li>
                            <li>Mandiri KCP Purbalingga, No. rekening xxxxxxxxxxx atas nama Yayasan BDK</li>
                        </ul>

                        <p>
                            Sekali lagi terima kasih atas semua dukungan dan bersama ini kami sampaikan
                            salam sukacita dari Oma dan Opa di Panti Wredha Budi Dharma Kasih.
                        </p>

                        {{-- TOMBOL DONASI (DISAMAIN DENGAN HTML LAMA) --}}
                        <div class="donasi-buttons">
                            <button
                                class="btn btn-donasi"
                                @click="pilihDonasi('barang')">
                                Formulir Donasi Barang
                            </button>

                            <button
                                class="btn btn-donasi"
                                @click="pilihDonasi('tunai')">
                                Formulir Donasi Tunai
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/donasi-pilihan.js') }}"></script>
@endpush
