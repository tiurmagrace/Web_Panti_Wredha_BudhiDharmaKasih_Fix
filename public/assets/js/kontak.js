/* ========================================
   KONTAK/FEEDBACK - TERHUBUNG KE DATABASE
   ======================================== */

// Wait for DOM ready to avoid Vue conflicts
document.addEventListener('DOMContentLoaded', function() {
    const kontakEl = document.getElementById('kontakApp');
    if (!kontakEl) return;
    
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                isLoggedIn: false,
                currentUser: null,
                isLoading: false,
                searchQuery: '',
                form: { nama: '', email: '', telepon: '', pesan: '' }
            }
        },
        mounted() {
            const status = localStorage.getItem('isLoggedIn');
            const userData = localStorage.getItem('user_data');

            if (status === 'true' && userData) {
                this.isLoggedIn = true;
                try {
                    this.currentUser = JSON.parse(userData);
                    this.form.nama = this.currentUser.nama || '';
                    this.form.email = this.currentUser.email || '';
                } catch (e) {
                    console.error('Error parsing user data:', e);
                }
            }
        },
        methods: {
            async kirimPesan() {
                if (!this.form.nama || !this.form.pesan) {
                    Swal.fire({
                        icon: 'warning', title: 'Data Belum Lengkap',
                        text: 'Harap isi Nama dan Pesan Anda!', confirmButtonColor: '#1a5c7a'
                    });
                    return;
                }

                this.isLoading = true;
                try {
                    const response = await fetch('/api/feedback', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nama: this.form.nama,
                            email: this.form.email,
                            telepon: this.form.telepon,
                            pesan: this.form.pesan
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        Swal.fire({
                            title: 'Pesan Terkirim!',
                            text: 'Terima kasih atas masukan dan dukungan Anda.',
                            icon: 'success', confirmButtonColor: '#1a5c7a'
                        });
                        this.form.pesan = '';
                    } else {
                        throw new Error(data.message || 'Gagal mengirim pesan');
                    }
                } catch (error) {
                    Swal.fire('Error', error.message, 'error');
                } finally {
                    this.isLoading = false;
                }
            },

            confirmLogout() {
                Swal.fire({
                    title: 'Keluar?', text: 'Apakah Anda yakin ingin logout?', icon: 'warning',
                    showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Logout'
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
    }).mount('#kontakApp');
});
