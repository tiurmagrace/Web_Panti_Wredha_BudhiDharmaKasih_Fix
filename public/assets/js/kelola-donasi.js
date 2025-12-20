// ==========================================
// kelola-donasi.js - FIXED activePage
// ==========================================

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', 
            alertStatus: null, 
            currentPage: 1, 
            itemsPerPage: 20,
            showFilter: false, 
            unreadCount: 0, 
            currentUrl: window.location.href,
            activePage: 'donasi', // ✅ FIXED: ganti dari currentPage
            filterJenis: '', 
            filterStatus: '', 
            filterPetugas: '',
            filterTanggalMulai: '', 
            filterTanggalSelesai: '',
            donasiList: []
        }
    },
    computed: {
        uniqueJenis() { return [...new Set(this.donasiList.map(item => item.jenis))]; },
        uniqueStatus() { return [...new Set(this.donasiList.map(item => item.status))]; },
        uniquePetugas() { return [...new Set(this.donasiList.map(item => item.petugas))]; },
        activeFiltersCount() {
            let count = 0;
            if (this.filterJenis) count++;
            if (this.filterStatus) count++;
            if (this.filterPetugas) count++;
            if (this.filterTanggalMulai || this.filterTanggalSelesai) count++;
            return count;
        },
        filteredList() {
            let hasil = this.donasiList.filter(item => {
                const search = this.searchQuery.toLowerCase();
                const matchSearch = (item.donatur && item.donatur.toLowerCase().includes(search)) ||
                                  (item.detail && item.detail.toLowerCase().includes(search)) ||
                                  (item.jumlah && item.jumlah.toLowerCase().includes(search)) ||
                                  (item.petugas && item.petugas.toLowerCase().includes(search));
                const matchJenis = !this.filterJenis || item.jenis === this.filterJenis;
                const matchStatus = !this.filterStatus || item.status === this.filterStatus;
                const matchPetugas = !this.filterPetugas || item.petugas === this.filterPetugas;
                let matchTanggal = true;
                if (this.filterTanggalMulai || this.filterTanggalSelesai) {
                    const itemDate = new Date(item.tanggal_raw);
                    if (this.filterTanggalMulai && itemDate < new Date(this.filterTanggalMulai)) matchTanggal = false;
                    if (this.filterTanggalSelesai && itemDate > new Date(this.filterTanggalSelesai)) matchTanggal = false;
                }
                return matchSearch && matchJenis && matchStatus && matchPetugas && matchTanggal;
            });

            hasil.sort((a, b) => {
                return (b.id || 0) - (a.id || 0);
            });

            return hasil;
        },
        totalPages() { return Math.ceil(this.filteredList.length / this.itemsPerPage); },
        paginatedList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredList.slice(start, start + this.itemsPerPage);
        }
    },
    watch: { filteredList() { this.currentPage = 1; } },
    mounted() {
        console.log('✅ Kelola Donasi Mounted!');
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success') this.alertStatus = 'success';
        if(urlParams.get('status') === 'edited') this.alertStatus = 'edited';
        if(this.alertStatus) window.history.replaceState({}, document.title, window.location.pathname);
        
        const dataBaru = JSON.parse(localStorage.getItem('donasiList'));
        if (dataBaru && Array.isArray(dataBaru) && dataBaru.length > 0) {
            this.donasiList = dataBaru;
            console.log('📦 Loaded', dataBaru.length, 'donasi from localStorage');
        } else {
            // Dummy data kalau belum ada
            this.donasiList = [
                { 
                    id: 1, 
                    tanggal: '12/04/2025', 
                    tanggal_raw: '2025-04-12', 
                    donatur: 'Ganjar', 
                    jenis: 'Barang', 
                    detail: 'Pakaian', 
                    jumlah: '1 Karung', 
                    status: 'Langsung', 
                    petugas: 'Pak Veri' 
                },
                { 
                    id: 2, 
                    tanggal: '12/04/2025', 
                    tanggal_raw: '2025-04-12', 
                    donatur: 'Prabowo', 
                    jenis: 'Barang', 
                    detail: 'Sembako', 
                    jumlah: '1 Paket', 
                    status: 'Langsung', 
                    petugas: 'Pak Veri' 
                }
            ];
            localStorage.setItem('donasiList', JSON.stringify(this.donasiList));
            console.log('💾 Created dummy data with', this.donasiList.length, 'items');
        }
    },
    methods: {
        formatTitleCase(str) {
            if (!str) return '-';
            return String(str).toLowerCase().replace(/(?:^|\s)\w/g, m => m.toUpperCase());
        },
        resetFilter() {
            this.filterJenis = ''; this.filterStatus = ''; this.filterPetugas = '';
            this.filterTanggalMulai = ''; this.filterTanggalSelesai = ''; this.searchQuery = '';
        },
        goToEditPage(item) {
            const realIndex = this.donasiList.findIndex(x => x === item);
            localStorage.setItem('editDonasiData', JSON.stringify({ index: realIndex, data: item }));
            window.location.href = '/admin/edit-donasi';
        },
        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },
        logoutAdmin() {
            Swal.fire({ title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
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