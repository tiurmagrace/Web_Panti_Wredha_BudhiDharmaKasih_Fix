/* ========================================
   DATA BARANG - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', startDate: '', endDate: '', filterType: 'tgl_masuk',
            currentPage: 1, itemsPerPage: 10, isModalOpen: false, modalMode: 'detail',
            tempFormData: {}, editingId: null, barangList: [], isLoading: true, // Start with loading true
            unreadCount: 0, currentUrl: window.location.href, activePage: 'barang'
        }
    },
    computed: {
        filteredList() {
            return this.barangList.filter(item => {
                const query = this.searchQuery.toLowerCase();
                const matchSearch = 
                    (item.nama && item.nama.toLowerCase().includes(query)) ||
                    (item.kategori && item.kategori.toLowerCase().includes(query));
                let matchDate = true;
                if (this.startDate && this.endDate) {
                    const targetDate = this.filterType === 'tgl_masuk' ? new Date(item.tgl_masuk) : new Date(item.expired);
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);
                    if (!targetDate || isNaN(targetDate)) matchDate = false;
                    else matchDate = targetDate >= start && targetDate <= end;
                }
                return matchSearch && matchDate;
            });
        },
        totalPages() { return Math.ceil(this.filteredList.length / this.itemsPerPage); },
        paginatedList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredList.slice(start, start + this.itemsPerPage);
        },
        totalStok() { return this.barangList.length; },
        stokMenipis() { return this.barangList.filter(i => i.sisa_stok < 5).length; },
        jumlahHampirExpired() {
            const today = new Date();
            return this.barangList.filter(item => {
                if (!item.expired) return false;
                const expDate = new Date(item.expired);
                const diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
                return diffDays <= 30 && diffDays >= 0;
            }).length;
        }
    },
    mounted() {
        console.log('✅ Data Barang Mounted!');
        this.loadBarang();
    },
    methods: {
        async loadBarang() {
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/barang', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.barangList = data.data;
                }
            } catch (error) {
                console.error('Error loading barang:', error);
            } finally {
                this.isLoading = false;
            }
        },

        formatTanggal(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            if (isNaN(d)) return '-';
            return `${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
        },

        resetFilter() {
            this.startDate = ''; this.endDate = ''; this.searchQuery = ''; this.filterType = 'tgl_masuk';
        },

        isStokMenipis(stok) { return stok < 5; },

        openModal(item, mode) {
            this.tempFormData = { ...item };
            this.modalMode = mode;
            this.editingId = item.id;
            this.isModalOpen = true;
        },

        closeModal() { this.isModalOpen = false; this.tempFormData = {}; this.editingId = null; },

        async processEdit() {
            const result = await Swal.fire({
                title: 'Simpan Perubahan?', icon: 'question', showCancelButton: true,
                confirmButtonColor: '#1a5c7a', cancelButtonColor: '#d33'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/barang/${this.editingId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify(this.tempFormData)
                    });

                    const data = await response.json();
                    if (data.success) {
                        await this.loadBarang();
                        this.closeModal();
                        Swal.fire('Berhasil', 'Data barang berhasil diupdate', 'success');
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                }
            }
        },

        async deleteItem(item) {
            const result = await Swal.fire({
                title: 'Yakin Hapus Barang?', text: item.nama, icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Ya, Hapus', confirmButtonColor: '#dc3545'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/barang/${item.id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': 'Bearer ' + token }
                    });
                    const data = await response.json();
                    if (data.success) {
                        await this.loadBarang();
                        Swal.fire('Terhapus!', 'Data barang telah dihapus.', 'success');
                    }
                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                }
            }
        },

        isNearExpiry(dateStr) {
            if (!dateStr) return false;
            const expDate = new Date(dateStr);
            const today = new Date();
            const diffDays = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
            return diffDays <= 30 && diffDays > 0;
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
