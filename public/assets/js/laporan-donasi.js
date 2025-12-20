// ==========================================
// 2. laporan-donasi.js - FIXED
// ==========================================
// File: public/assets/js/laporan-donasi.js

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', alertStatus: null, unreadCount: 0,
            currentUrl: window.location.href, currentPage: 'donasi',
            filterJenis: '', filterBulan: '', filterTahun: '',
            donasiList: [
                { id: 1, tanggal: '12/04/2025', donatur: 'Ganjar', jenis: 'Barang', detail: 'Pakaian', jumlah: '1 Karung', status: 'Langsung', petugas: 'Pak Veri' },
                { id: 2, tanggal: '15/04/2025', donatur: 'Hamba Allah', jenis: 'Tunai', detail: 'Uang Tunai', jumlah: '1.000.000', status: 'Tidak Langsung', petugas: 'Pak Veri' }
            ]
        }
    },
    computed: {
        filteredList() {
            return this.donasiList.filter(item => {
                const search = this.searchQuery.toLowerCase();
                const matchSearch = (item.donatur && item.donatur.toLowerCase().includes(search)) ||
                                  (item.jenis && item.jenis.toLowerCase().includes(search)) ||
                                  (item.detail && item.detail.toLowerCase().includes(search));
                const matchJenis = this.filterJenis ? item.jenis === this.filterJenis : true;
                let matchTanggal = true;
                if (this.filterBulan || this.filterTahun) {
                    if (item.tanggal && item.tanggal.includes('/')) {
                        const parts = item.tanggal.split('/');
                        const monthData = parts[1];
                        const yearData = parts[2];
                        if (this.filterBulan && monthData !== this.filterBulan) matchTanggal = false;
                        if (this.filterTahun && yearData !== this.filterTahun) matchTanggal = false;
                    } else {
                        matchTanggal = false;
                    }
                }
                return matchSearch && matchJenis && matchTanggal;
            });
        }
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'sent') {
            this.alertStatus = 'sent';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        const dataBaru = JSON.parse(localStorage.getItem('donasiList'));
        if (dataBaru && Array.isArray(dataBaru)) {
            dataBaru.forEach(item => {
                if(!this.donasiList.some(d => d.donatur === item.donatur && d.jumlah === item.jumlah)) {
                    this.donasiList.unshift(item);
                }
            });
        }
    },
    methods: {
        resetFilter() {
            this.filterJenis = ''; this.filterBulan = ''; this.filterTahun = '';
            this.searchQuery = '';
        },
        goToGeneratePage(item) {
            localStorage.setItem('selectedDonasiForReport', JSON.stringify(item));
            window.location.href = '/admin/generate-laporan';
        },
        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout', cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('adminLoggedIn');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');