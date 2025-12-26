// ==========================================
// 2. laporan-donasi.js - CONNECTED TO API
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
            isLoading: false,
            donasiList: []
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
                    if (item.tanggal) {
                        const d = new Date(item.tanggal);
                        const monthData = String(d.getMonth() + 1).padStart(2, '0');
                        const yearData = String(d.getFullYear());
                        if (this.filterBulan && monthData !== this.filterBulan) matchTanggal = false;
                        if (this.filterTahun && yearData !== this.filterTahun) matchTanggal = false;
                    } else {
                        matchTanggal = false;
                    }
                }
                return matchSearch && matchJenis && matchTanggal;
            });
        },
        // Get unique years from data for filter
        availableYears() {
            const years = new Set();
            this.donasiList.forEach(item => {
                if (item.tanggal) {
                    const d = new Date(item.tanggal);
                    years.add(String(d.getFullYear()));
                }
            });
            return Array.from(years).sort((a, b) => b - a);
        }
    },
    mounted() {
        this.loadDonasi();
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'sent') {
            this.alertStatus = 'sent';
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    },
    methods: {
        async loadDonasi() {
            this.isLoading = true;
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/donasi', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.donasiList = data.data.filter(d => d.status_verifikasi === 'approved');
                }
            } catch (error) {
                console.error('Error loading donasi:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
        },
        
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
                    localStorage.removeItem('admin_token');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');