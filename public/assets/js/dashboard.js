/* ========================================
   DASHBOARD ADMIN - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', notifications: [], displayedNotifications: [],
            unreadCount: 0, activities: [], feedbacks: [],
            totalPenghuni: 0, totalUang: 0, totalBarang: 0, totalStok: 0,
            stokMenipis: 0, jumlahHampirExpired: 0,
            totalSembako: 0, totalPakaian: 0, totalObat: 0,
            currentUrl: window.location.href, activePage: 'dashboard',
            isLoading: false
        }
    },
    computed: {
        filteredFeedbacks() {
            if (!this.searchQuery) return this.feedbacks;
            const query = this.searchQuery.toLowerCase();
            return this.feedbacks.filter(item => 
                (item.nama && item.nama.toLowerCase().includes(query)) ||
                (item.pesan && item.pesan.toLowerCase().includes(query))
            );
        },
        filteredActivities() {
            if (!this.searchQuery) return this.activities;
            const query = this.searchQuery.toLowerCase();
            return this.activities.filter(act => 
                (act.text && act.text.toLowerCase().includes(query))
            );
        }
    },
    mounted() {
        console.log('✅ Dashboard Mounted!');
        this.loadAllData();
        setInterval(() => this.loadAllData(), 30000); // Refresh setiap 30 detik
    },
    methods: {
        async loadAllData() {
            this.isLoading = true;
            const token = localStorage.getItem('admin_token');
            const headers = { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token };

            try {
                // Load Penghuni Statistics
                const penghuniRes = await fetch('/api/penghuni/statistics', { headers });
                const penghuniData = await penghuniRes.json();
                if (penghuniData.success) {
                    this.totalPenghuni = penghuniData.data.total || 0;
                }

                // Load Donasi Statistics
                const donasiRes = await fetch('/api/donasi/statistics', { headers });
                const donasiData = await donasiRes.json();
                if (donasiData.success) {
                    this.totalUang = donasiData.data.total_tunai || 0;
                    this.totalBarang = donasiData.data.total_barang || 0;
                }

                // Load Barang Statistics
                const barangRes = await fetch('/api/barang/statistics', { headers });
                const barangData = await barangRes.json();
                if (barangData.success) {
                    this.totalStok = barangData.data.total || 0;
                    this.stokMenipis = barangData.data.stok_menipis || 0;
                    this.jumlahHampirExpired = barangData.data.hampir_expired || 0;
                }

                // Load Feedback
                const feedbackRes = await fetch('/api/feedback', { headers });
                const feedbackData = await feedbackRes.json();
                if (feedbackData.success) {
                    this.feedbacks = feedbackData.data.slice(0, 5);
                }

                // Load Aktivitas Log
                const aktivitasRes = await fetch('/api/aktivitas-log', { headers });
                const aktivitasData = await aktivitasRes.json();
                if (aktivitasData.success) {
                    this.activities = aktivitasData.data.slice(0, 5);
                }

                // Load Notifikasi
                const notifRes = await fetch('/api/notifikasi', { headers });
                const notifData = await notifRes.json();
                if (notifData.success) {
                    this.notifications = notifData.data;
                    this.displayedNotifications = notifData.data.slice(0, 5);
                    this.unreadCount = notifData.data.filter(n => !n.is_read).length;
                }

            } catch (error) {
                console.error('Error loading dashboard data:', error);
            } finally {
                this.isLoading = false;
            }
        },

        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        },

        truncateText(text, length) {
            if (!text) return '-';
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        },

        showFullMessage(item) {
            Swal.fire({
                title: `Pesan dari ${item.nama}`,
                html: `<div style="text-align: left;">${item.pesan}</div>`,
                confirmButtonText: 'Tutup', confirmButtonColor: '#1a5c7a'
            });
        },

        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', text: "Sesi admin akan diakhiri.", icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout'
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
