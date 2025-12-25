/* ========================================
   NOTIFIKASI ADMIN - TERHUBUNG KE DATABASE
   ======================================== */

if (!localStorage.getItem('adminLoggedIn')) {
    window.location.href = '/admin/login';
}

const { createApp } = window.Vue;

createApp({
    data() {
        return {
            searchQuery: '', 
            filterType: '', 
            notifications: [],
            currentUrl: window.location.href, 
            unreadCount: 0,
            isLoading: false
        }
    },
    computed: {
        filteredList() {
            return this.notifications.filter(item => {
                const query = this.searchQuery.toLowerCase();
                const allText = (
                    (item.title || '') + ' ' + (item.text || '') + ' ' + (item.type || '')
                ).toLowerCase();
                const matchSearch = allText.includes(query);
                const matchFilter = this.filterType ? item.type === this.filterType : true;
                return matchSearch && matchFilter;
            });
        }
    },
    mounted() {
        console.log('✅ Notifikasi Admin Mounted!');
        this.loadNotifications();
    },
    methods: {
        async loadNotifications() {
            this.isLoading = true;
            try {
                const token = localStorage.getItem('admin_token');
                const response = await fetch('/api/notifikasi', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.notifications = data.data.map(n => ({
                        ...n,
                        dateDisplay: this.formatDate(n.created_at || n.date)
                    }));
                    this.unreadCount = this.notifications.filter(n => n.status === 'unread').length;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            } finally {
                this.isLoading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getTypeLabel(type) {
            const labels = {
                'donasi_masuk': 'Donasi Masuk',
                'donasi': 'Donasi',
                'stok_menipis': 'Stok Menipis',
                'stok': 'Stok',
                'hampir_kadaluarsa': 'Hampir Kadaluarsa',
                'kadaluarsa': 'Kadaluarsa',
                'penghuni': 'Penghuni',
                'system': 'Sistem'
            };
            return labels[type] || type;
        },

        getTypeClass(type) {
            if (type.includes('donasi')) return 'badge-donasi';
            if (type.includes('stok') || type.includes('kadaluarsa')) return 'badge-stok';
            if (type.includes('penghuni')) return 'badge-penghuni';
            return 'badge-system';
        },

        async markAsRead(notif) {
            if (notif.status === 'read') return;
            
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
                console.error('Error marking as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const token = localStorage.getItem('admin_token');
                await fetch('/api/notifikasi/mark-all-as-read', {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    }
                });
                this.notifications.forEach(n => n.status = 'read');
                this.unreadCount = 0;
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Semua notifikasi ditandai sudah dibaca',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (error) {
                console.error('Error:', error);
            }
        },

        async deleteNotif(notif) {
            const result = await Swal.fire({
                title: 'Hapus Notifikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('admin_token');
                    await fetch(`/api/notifikasi/${notif.id}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    this.notifications = this.notifications.filter(n => n.id !== notif.id);
                    this.unreadCount = this.notifications.filter(n => n.status === 'unread').length;
                } catch (error) {
                    console.error('Error:', error);
                }
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
