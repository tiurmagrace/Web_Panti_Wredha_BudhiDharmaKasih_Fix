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
            searchQuery: '', 
            notifications: [], 
            displayedNotifications: [],
            unreadCount: 0, 
            activities: [], 
            feedbacks: [],
            // Statistik Penghuni
            totalPenghuni: 0,
            // Statistik Donasi
            totalDonasiTunai: 0,
            totalDonasiBarang: 0,
            donasiTunaiBulanIni: 0,
            donasiBarangBulanIni: 0,
            totalDonasiBulanIni: 0,
            kategoriBulanIni: {},
            pendingDonasi: 0,
            // Statistik Barang/Stok
            totalStok: 0,
            stokMenipis: 0, 
            jumlahHampirExpired: 0,
            // UI
            currentUrl: window.location.href, 
            activePage: 'dashboard',
            isLoading: true, // Start with loading
            dataLoaded: false
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
    },
    methods: {
        async loadAllData() {
            const token = localStorage.getItem('admin_token');
            const headers = { 
                'Accept': 'application/json', 
                'Authorization': 'Bearer ' + token 
            };

            try {
                // Load critical data first (statistics) - parallel
                const [penghuniRes, donasiRes, barangRes] = await Promise.all([
                    fetch('/api/penghuni/statistics', { headers }).catch(() => ({ ok: false })),
                    fetch('/api/donasi/admin/statistics', { headers }).catch(() => ({ ok: false })),
                    fetch('/api/barang/statistics', { headers }).catch(() => ({ ok: false }))
                ]);

                // Process critical data immediately
                if (penghuniRes.ok) {
                    const penghuniData = await penghuniRes.json();
                    if (penghuniData.success) {
                        this.totalPenghuni = penghuniData.data.total || 0;
                    }
                }

                if (donasiRes.ok) {
                    const donasiData = await donasiRes.json();
                    if (donasiData.success) {
                        this.totalDonasiTunai = donasiData.data.total_tunai || 0;
                        this.totalDonasiBarang = donasiData.data.total_barang || 0;
                        this.donasiTunaiBulanIni = donasiData.data.tunai_bulan_ini || 0;
                        this.donasiBarangBulanIni = donasiData.data.barang_bulan_ini || 0;
                        this.totalDonasiBulanIni = donasiData.data.total_donasi_bulan_ini || 0;
                        this.kategoriBulanIni = donasiData.data.kategori_bulan_ini || {};
                        this.pendingDonasi = donasiData.data.pending || 0;
                    }
                }

                if (barangRes.ok) {
                    const barangData = await barangRes.json();
                    if (barangData.success) {
                        this.totalStok = barangData.data.total || 0;
                        this.stokMenipis = barangData.data.stok_menipis || 0;
                        this.jumlahHampirExpired = barangData.data.hampir_expired || 0;
                    }
                }

                // Hide loading after critical data
                this.isLoading = false;
                this.dataLoaded = true;

                // Load secondary data (non-blocking)
                this.loadSecondaryData(headers);

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                this.isLoading = false;
            }
        },

        async loadSecondaryData(headers) {
            try {
                // Load feedback, aktivitas, notifikasi in background
                const [feedbackRes, aktivitasRes, notifRes] = await Promise.all([
                    fetch('/api/feedback', { headers }).catch(() => ({ ok: false })),
                    fetch('/api/aktivitas-log', { headers }).catch(() => ({ ok: false })),
                    fetch('/api/notifikasi', { headers }).catch(() => ({ ok: false }))
                ]);

                if (feedbackRes.ok) {
                    const feedbackData = await feedbackRes.json();
                    if (feedbackData.success) {
                        this.feedbacks = feedbackData.data.slice(0, 5);
                    }
                }

                if (aktivitasRes.ok) {
                    const aktivitasData = await aktivitasRes.json();
                    if (aktivitasData.success) {
                        this.activities = aktivitasData.data.slice(0, 5);
                    }
                }

                if (notifRes.ok) {
                    const notifData = await notifRes.json();
                    if (notifData.success) {
                        this.notifications = notifData.data;
                        this.displayedNotifications = notifData.data.slice(0, 5);
                        this.unreadCount = notifData.data.filter(n => n.status === 'unread').length;
                    }
                }
            } catch (error) {
                console.error('Error loading secondary data:', error);
            }
        },

        goToPendingDonasi() {
            window.location.href = '/admin/kelola-donasi?filter=pending';
        },

        formatRupiah(angka) {
            if (!angka) return '0';
            return new Intl.NumberFormat('id-ID').format(angka);
        },

        truncateText(text, length) {
            if (!text) return '-';
            if (text.length <= length) return text;
            return text.substring(0, length) + '...';
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        getNotifIcon(type) {
            const icons = {
                'donasi_masuk': 'fa-hand-holding-heart text-success',
                'stok_menipis': 'fa-box text-warning',
                'hampir_kadaluarsa': 'fa-clock text-orange',
                'kadaluarsa': 'fa-exclamation-triangle text-danger'
            };
            return icons[type] || 'fa-bell text-primary';
        },

        showFullMessage(item) {
            Swal.fire({
                title: `Pesan dari ${item.nama}`,
                html: `<div style="text-align: left;">${item.pesan}</div>
                       <small class="text-muted">${this.formatDate(item.tanggal)}</small>`,
                confirmButtonText: 'Tutup', 
                confirmButtonColor: '#1a5c7a'
            });
        },

        async markNotifAsRead(notif) {
            try {
                const token = localStorage.getItem('admin_token');
                await fetch(`/api/notifikasi/${notif.id}/mark-as-read`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                notif.status = 'read';
                this.unreadCount = this.notifications.filter(n => n.status === 'unread').length;
            } catch (error) {
                console.error('Error:', error);
            }
        },

        logoutAdmin() {
            Swal.fire({
                title: 'Keluar?', 
                text: "Sesi admin akan diakhiri.", 
                icon: 'warning',
                showCancelButton: true, 
                confirmButtonColor: '#d33', 
                cancelButtonColor: '#3085d6',
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
