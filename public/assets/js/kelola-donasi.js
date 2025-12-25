/* ========================================
   KELOLA DONASI - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', alertStatus: null, currentPage: 1, itemsPerPage: 20,
            showFilter: false, unreadCount: 0, currentUrl: window.location.href,
            activePage: 'donasi', isLoading: false,
            filterJenis: '', filterStatus: '', filterPetugas: '', filterVerifikasi: '',
            filterTanggalMulai: '', filterTanggalSelesai: '',
            donasiList: []
        }
    },
    computed: {
        uniqueJenis() { return [...new Set(this.donasiList.map(item => item.jenis).filter(Boolean))]; },
        uniqueStatus() { return [...new Set(this.donasiList.map(item => item.status).filter(Boolean))]; },
        uniquePetugas() { return [...new Set(this.donasiList.map(item => item.petugas).filter(Boolean))]; },
        activeFiltersCount() {
            let count = 0;
            if (this.filterJenis) count++;
            if (this.filterStatus) count++;
            if (this.filterPetugas) count++;
            if (this.filterVerifikasi) count++;
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
                const matchVerifikasi = !this.filterVerifikasi || item.status_verifikasi === this.filterVerifikasi;
                let matchTanggal = true;
                if (this.filterTanggalMulai || this.filterTanggalSelesai) {
                    const itemDate = new Date(item.tanggal);
                    if (this.filterTanggalMulai && itemDate < new Date(this.filterTanggalMulai)) matchTanggal = false;
                    if (this.filterTanggalSelesai && itemDate > new Date(this.filterTanggalSelesai)) matchTanggal = false;
                }
                return matchSearch && matchJenis && matchStatus && matchPetugas && matchTanggal && matchVerifikasi;
            });
            return hasil.sort((a, b) => (b.id || 0) - (a.id || 0));
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
        this.loadDonasi();
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success') this.alertStatus = 'success';
        if(urlParams.get('status') === 'edited') this.alertStatus = 'edited';
        
        // Handle filter dari URL (dari dashboard)
        if(urlParams.get('filter') === 'pending') {
            this.filterVerifikasi = 'pending';
            this.showFilter = true;
        }
        
        if(this.alertStatus) window.history.replaceState({}, document.title, window.location.pathname);
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
                    this.donasiList = data.data;
                }
            } catch (error) {
                console.error('Error loading donasi:', error);
            } finally {
                this.isLoading = false;
            }
        },

        formatTitleCase(str) {
            if (!str) return '-';
            return String(str).toLowerCase().replace(/(?:^|\s)\w/g, m => m.toUpperCase());
        },

        formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
        },

        resetFilter() {
            this.filterJenis = ''; this.filterStatus = ''; this.filterPetugas = '';
            this.filterVerifikasi = ''; this.filterTanggalMulai = ''; this.filterTanggalSelesai = ''; 
            this.searchQuery = '';
        },

        goToEditPage(item) {
            localStorage.setItem('editDonasiData', JSON.stringify({ id: item.id, data: item }));
            window.location.href = '/admin/edit-donasi';
        },

        async verifyDonasi(item, status) {
            const isApprove = status === 'approved';
            const title = isApprove ? 'Setujui Donasi?' : 'Tolak Donasi?';
            const text = isApprove 
                ? `Donasi dari ${item.donatur} akan disetujui dan donatur akan menerima notifikasi.`
                : `Donasi dari ${item.donatur} akan ditolak.`;
            
            const swalConfig = {
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isApprove ? '#28a745' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isApprove ? 'Ya, Setujui' : 'Ya, Tolak',
                cancelButtonText: 'Batal'
            };

            // Tambahkan input alasan hanya untuk reject
            if (!isApprove) {
                swalConfig.input = 'textarea';
                swalConfig.inputLabel = 'Alasan penolakan (opsional)';
                swalConfig.inputPlaceholder = 'Masukkan alasan...';
            }

            const result = await Swal.fire(swalConfig);

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    
                    // Siapkan body request
                    const bodyData = {
                        status_verifikasi: status
                    };
                    
                    // Tambahkan catatan hanya jika ada value dan itu string
                    if (result.value && typeof result.value === 'string' && result.value.trim() !== '') {
                        bodyData.catatan = result.value.trim();
                    }

                    const response = await fetch(`/api/donasi/${item.id}/verify`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(bodyData)
                    });

                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        // Update item di list
                        item.status_verifikasi = status;
                        
                        Swal.fire({
                            icon: 'success',
                            title: isApprove ? 'Donasi Disetujui!' : 'Donasi Ditolak',
                            text: isApprove ? 'Notifikasi telah dikirim ke donatur.' : 'Status donasi telah diupdate.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Gagal memverifikasi');
                    }
                } catch (error) {
                    console.error('Verify error:', error);
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        },

        async sendThankYou(item) {
            const result = await Swal.fire({
                title: 'Kirim Ucapan Terima Kasih',
                html: `<p>Kirim ucapan terima kasih ke <strong>${item.donatur}</strong>?</p>`,
                input: 'textarea',
                inputLabel: 'Pesan (opsional, kosongkan untuk pesan default)',
                inputPlaceholder: 'Terima kasih atas donasi Anda...',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                confirmButtonText: '<i class="fas fa-heart"></i> Kirim',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/donasi/${item.id}/thank-you`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            pesan: result.value || null
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terkirim!',
                            text: 'Ucapan terima kasih telah dikirim ke donatur.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Gagal mengirim');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        },

        async deleteDonasi(item) {
            const result = await Swal.fire({
                title: 'Hapus Donasi?', text: `Yakin hapus donasi dari ${item.donatur}?`, icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/donasi/${item.id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': 'Bearer ' + token }
                    });
                    const data = await response.json();
                    if (data.success) {
                        await this.loadDonasi();
                        Swal.fire({ icon: 'success', title: 'Terhapus!', timer: 1500 });
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        },

        prevPage() { if (this.currentPage > 1) this.currentPage--; },
        nextPage() { if (this.currentPage < this.totalPages) this.currentPage++; },

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
