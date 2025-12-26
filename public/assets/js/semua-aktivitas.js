// ==========================================
// 2. semua-aktivitas.js - TERHUBUNG KE DATABASE
// ==========================================
// File: public/assets/js/semua-aktivitas.js

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', 
            filterKategori: '', 
            activities: [],
            isLoading: false,
            currentUrl: window.location.href, 
            unreadCount: 0
        }
    },
    computed: {
        filteredList() {
            let result = this.activities.filter(item => {
                const text = (item.text || '').toLowerCase();
                const kategori = (item.kategori || '').toLowerCase();
                return text.includes(this.searchQuery.toLowerCase()) ||
                       kategori.includes(this.searchQuery.toLowerCase());
            });
            if (this.filterKategori) {
                result = result.filter(item => item.kategori === this.filterKategori);
            }
            return result;
        },
        uniqueKategori() {
            return [...new Set(this.activities.map(a => a.kategori).filter(Boolean))];
        }
    },
    mounted() {
        this.loadActivities();
    },
    methods: {
        async loadActivities() {
            this.isLoading = true;
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/aktivitas-log', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.activities = data.data;
                }
            } catch (error) {
                console.error('Error loading activities:', error);
            } finally {
                this.isLoading = false;
            }
        },
        
        formatTanggal(waktu) {
            if (!waktu) return '-';
            try {
                const date = new Date(waktu);
                if (isNaN(date.getTime())) return waktu;
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'short', year: 'numeric',
                    hour: '2-digit', minute: '2-digit'
                });
            } catch (e) {
                return waktu;
            }
        },
        
        getKategoriClass(kategori) {
            const classes = {
                'Donasi': 'bg-success',
                'Barang': 'bg-warning text-dark',
                'Penghuni': 'bg-info',
                'System': 'bg-secondary'
            };
            return classes[kategori] || 'bg-primary';
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