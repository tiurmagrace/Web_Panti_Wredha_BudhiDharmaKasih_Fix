/* ========================================
   NOTIFIKASI DONATUR - TERHUBUNG KE DATABASE
   ======================================== */

// Wait for DOM ready to avoid Vue conflicts
document.addEventListener('DOMContentLoaded', function() {
    const notifEl = document.getElementById('notifikasiApp');
    if (!notifEl) return;
    
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                isLoggedIn: false,
                currentUser: null,
                searchQuery: '',
                notifications: [],
                unreadCount: 0,
                isLoading: true
            };
        },

        computed: {
            filteredNotifications() {
                if (!this.searchQuery) return this.notifications;
                const query = this.searchQuery.toLowerCase();
                return this.notifications.filter(n => 
                    (n.title && n.title.toLowerCase().includes(query)) ||
                    (n.text && n.text.toLowerCase().includes(query))
                );
            }
        },

        mounted() {
            this.checkAuth();
        },

        methods: {
            checkAuth() {
                const status = localStorage.getItem('isLoggedIn');
                const token = localStorage.getItem('auth_token');
                const userData = localStorage.getItem('user_data');

                if (status !== 'true' || !token || !userData) {
                    this.isLoading = false;
                    localStorage.setItem('redirect_after_login', '/donatur/notifikasi');
                    Swal.fire({
                        icon: 'info',
                        title: 'Login Diperlukan',
                        text: 'Silakan login terlebih dahulu untuk melihat notifikasi.',
                        confirmButtonColor: '#1a5c7a'
                    }).then(() => {
                        window.location.href = '/auth/login';
                    });
                    return;
                }

                this.isLoggedIn = true;
                try {
                    this.currentUser = JSON.parse(userData);
                } catch (e) {
                    console.error('Error parsing user data:', e);
                }
                this.loadNotifications();
            },

            async loadNotifications() {
                try {
                    const token = localStorage.getItem('auth_token');
                    const response = await fetch('/api/notifikasi', {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.notifications = data.data;
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

            getNotifIcon(type) {
                const icons = {
                    'donasi_diterima': 'fa-check-circle text-success',
                    'donasi_ditolak': 'fa-times-circle text-danger',
                    'ucapan_terimakasih': 'fa-heart text-danger',
                    'donasi': 'fa-hand-holding-heart text-primary'
                };
                return icons[type] || 'fa-bell text-secondary';
            },

            async markAsRead(notif) {
                if (notif.status === 'read') return;
                
                try {
                    const token = localStorage.getItem('auth_token');
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

            async markAllAsRead() {
                try {
                    const token = localStorage.getItem('auth_token');
                    await fetch('/api/notifikasi/mark-all-as-read', {
                        method: 'PATCH',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        }
                    });
                    this.notifications.forEach(n => n.status = 'read');
                    this.unreadCount = 0;
                } catch (error) {
                    console.error('Error:', error);
                }
            },

            showNotifDetail(notif) {
                this.markAsRead(notif);
                Swal.fire({
                    title: notif.title,
                    html: `<p style="text-align: left;">${notif.text}</p>
                           <small class="text-muted">${this.formatDate(notif.created_at)}</small>`,
                    confirmButtonColor: '#1a5c7a'
                });
            },

            logout() {
                Swal.fire({
                    title: 'Logout?',
                    text: 'Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        localStorage.removeItem('isLoggedIn');
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_data');
                        window.location.href = '/';
                    }
                });
            }
        }
    }).mount('#notifikasiApp');
});
