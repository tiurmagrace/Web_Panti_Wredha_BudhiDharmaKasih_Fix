





// ==========================================
// 3. semua-feedback.js - FIXED FOR LARAVEL
// ==========================================
// File: public/assets/js/semua-feedback.js

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', feedbacks: [], filterBulan: '', filterTahun: '',
            currentUrl: window.location.href, unreadCount: 0
        }
    },
    computed: {
        filteredList() {
            return this.feedbacks.filter(item => {
                const search = this.searchQuery.toLowerCase();
                const matchSearch = (item.nama && item.nama.toLowerCase().includes(search)) ||
                                  (item.pesan && item.pesan.toLowerCase().includes(search));
                let matchDate = true;
                if (this.filterBulan || this.filterTahun) {
                    if (item.tanggal && item.tanggal.includes('/')) {
                        const parts = item.tanggal.split('/');
                        const monthData = parts[1];
                        const yearData = parts[2];
                        if (this.filterBulan && monthData !== this.filterBulan) matchDate = false;
                        if (this.filterTahun && yearData !== this.filterTahun) matchDate = false;
                    } else {
                        matchDate = false;
                    }
                }
                return matchSearch && matchDate;
            });
        }
    },
    mounted() {
        const data = JSON.parse(localStorage.getItem('feedbackList')) || [];
        this.feedbacks = data.sort((a, b) => b.id - a.id);
    },
    methods: {
        resetFilter() {
            this.filterBulan = ''; this.filterTahun = ''; this.searchQuery = '';
        },
        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('adminLoggedIn');
                    window.location.href = '/admin/login';
                }
            });
        }
    }
}).mount('#adminApp');