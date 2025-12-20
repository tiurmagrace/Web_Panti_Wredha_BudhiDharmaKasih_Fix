// ==========================================
// 2. semua-aktivitas.js - FIXED FOR LARAVEL
// ==========================================
// File: public/assets/js/semua-aktivitas.js

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', filterKategori: '', activities: [],
            currentUrl: window.location.href, unreadCount: 0
        }
    },
    computed: {
        filteredList() {
            let result = this.activities.filter(item => {
                return item.text.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
            if (this.filterKategori) {
                result = result.filter(item => {
                    return item.text.toLowerCase().includes(this.filterKategori.toLowerCase());
                });
            }
            return result;
        }
    },
    mounted() {
        const logs = JSON.parse(localStorage.getItem('activityLog')) || [];
        this.activities = logs.reverse();
    },
    methods: {
        formatTanggal(waktu) {
            if (!waktu || typeof waktu === 'string' && waktu.length < 10) {
                return waktu;
            }
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