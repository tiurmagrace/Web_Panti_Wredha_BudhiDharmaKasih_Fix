/* ========================================
   KELOLA PENGHUNI - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            alertStatus: null, isModalOpen: false, modalMode: 'detail',
            currentPage: 1, itemsPerPage: 20, tempFormData: {}, editingId: null,
            searchQuery: '', filterPaviliun: '', filterTahun: '',
            penghuniList: [], isLoading: false,
            currentUrl: window.location.href, activePage: 'penghuni', unreadCount: 0
        }
    },
    
    computed: {
        uniqueYears() {
            if (!this.penghuniList || this.penghuniList.length === 0) return [];
            const years = this.penghuniList.map(item => {
                if (item.tgl_masuk) {
                    return new Date(item.tgl_masuk).getFullYear().toString();
                }
                return null;
            }).filter(Boolean);
            return [...new Set(years)].sort((a, b) => b - a);
        },

        filteredList() {
            return this.penghuniList.filter(item => {
                const search = this.searchQuery.toLowerCase();
                const matchSearch = (item.nik && item.nik.toString().toLowerCase().includes(search)) ||
                                    (item.nama && item.nama.toLowerCase().includes(search)) ||
                                    (item.kota && item.kota.toLowerCase().includes(search));
                const matchPaviliun = this.filterPaviliun === '' || 
                                    (item.paviliun && item.paviliun.toUpperCase() === this.filterPaviliun.toUpperCase());
                const itemYear = item.tgl_masuk ? new Date(item.tgl_masuk).getFullYear().toString() : '';
                const matchTahun = this.filterTahun === '' || itemYear === this.filterTahun;
                return matchSearch && matchPaviliun && matchTahun;
            }).sort((a, b) => new Date(b.tgl_masuk) - new Date(a.tgl_masuk));
        },

        totalPages() { return Math.ceil(this.filteredList.length / this.itemsPerPage); },
        paginatedList() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredList.slice(start, start + this.itemsPerPage);
        }
    },

    mounted() {
        console.log('✅ Vue Kelola Penghuni Mounted!');
        this.loadDataPenghuni();
        
        const urlParams = new URLSearchParams(window.location.search);
        if(urlParams.get('status') === 'success') {
            this.alertStatus = 'success';
            Swal.fire({ icon: 'success', title: 'Data Berhasil Ditambahkan!', confirmButtonColor: '#21698a', timer: 3000 });
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    },

    methods: {
        async loadDataPenghuni() {
            this.isLoading = true;
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/penghuni', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.penghuniList = data.data;
                }
            } catch (error) {
                console.error('Error loading penghuni:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        formatUpperCase(str) { return str ? String(str).toUpperCase() : '-'; },
        formatTitleCase(str) { return str ? String(str).toLowerCase().replace(/(?:^|\s)\w/g, m => m.toUpperCase()) : '-'; },
        
        resetFilter() {
            this.filterPaviliun = ''; this.filterTahun = ''; this.searchQuery = ''; this.currentPage = 1;
        },

        triggerFileInput() {
            if (this.modalMode === 'edit') this.$refs.fileInput.click();
        },

        handlePhotoUpload(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => { this.tempFormData.foto = ev.target.result; };
                reader.readAsDataURL(file);
            }
        },

        openModal(item, mode) {
            this.modalMode = mode;
            this.tempFormData = { ...item };
            this.editingId = item.id;
            this.isModalOpen = true;
        },

        closeModal() {
            this.isModalOpen = false; this.tempFormData = {}; this.editingId = null;
        },

        async processEdit() {
            if (!this.editingId) return;
            
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch(`/api/penghuni/${this.editingId}`, {
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
                    await this.loadDataPenghuni();
                    Swal.fire({ icon: 'success', title: 'Data Berhasil Diubah!', confirmButtonColor: '#21698a' });
                    this.closeModal();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: error.message });
            }
        },

        async deletePenghuni(item) {
            const result = await Swal.fire({
                title: 'Hapus Data?', text: `Yakin hapus data ${item.nama}?`, icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    const response = await fetch(`/api/penghuni/${item.id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': 'Bearer ' + token }
                    });
                    const data = await response.json();
                    if (data.success) {
                        await this.loadDataPenghuni();
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
                title: 'Konfirmasi Logout', text: 'Apakah Anda yakin ingin keluar?', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#21698a', cancelButtonColor: '#d33',
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
