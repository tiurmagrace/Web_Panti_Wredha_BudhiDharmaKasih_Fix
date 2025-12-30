/* ========================================
   DONASI TUNAI - TERHUBUNG KE DATABASE
   ======================================== */

// Wait for DOM ready to avoid Vue conflicts
document.addEventListener('DOMContentLoaded', function() {
    const tunaiEl = document.getElementById('tunaiApp');
    if (!tunaiEl) return;
    
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                isLoggedIn: false,
                currentUser: null,
                isLoading: false,
                form: {
                    nama: '', hp: '', email: '', jumlah: '', catatan: '', file: null
                },
                errors: {}
            };
        },

        mounted() {
            const status = localStorage.getItem('isLoggedIn');
            const userData = localStorage.getItem('user_data');

            if (status !== 'true' || !userData) {
                localStorage.setItem('redirect_after_login', '/donatur/donasi-tunai');
                Swal.fire({
                    icon: 'info', title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu untuk mengisi formulir donasi.',
                    confirmButtonColor: '#1a5c7a'
                }).then(() => { window.location.href = '/auth/login'; });
                return;
            }

            this.isLoggedIn = true;
            try {
                this.currentUser = JSON.parse(userData);
                this.form.nama = this.currentUser.nama || '';
                this.form.email = this.currentUser.email || '';
            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        },

        methods: {
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) { this.form.file = null; return; }
                const reader = new FileReader();
                reader.onload = e => { this.form.file = e.target.result; };
                reader.readAsDataURL(file);
            },

            async kirimDonasi() {
                this.errors = {};
                if (!this.form.hp) this.errors.hp = 'Nomor HP wajib diisi';
                if (!this.form.jumlah) this.errors.jumlah = 'Jumlah donasi wajib diisi';
                if (!this.form.file) this.errors.file = 'Bukti transfer wajib diupload';
                if (Object.keys(this.errors).length > 0) return;

                this.isLoading = true;
                try {
                    const token = localStorage.getItem('auth_token');
                    const response = await fetch('/api/donasi', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            donatur: this.form.nama,
                            jenis: 'Tunai',
                            detail: 'Uang Tunai',
                            jumlah: this.form.jumlah,
                            tanggal: new Date().toISOString().split('T')[0],
                            status: 'Tidak Langsung',
                            petugas: '-'
                        })
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        const modalEl = document.getElementById('modalSuccess');
                        if (modalEl) {
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                        this.form.hp = ''; this.form.jumlah = ''; this.form.catatan = ''; this.form.file = null;
                        const fileInput = document.querySelector('input[type="file"]');
                        if (fileInput) fileInput.value = '';
                    } else {
                        throw new Error(data.message || 'Gagal mengirim donasi');
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
                    showCancelButton: true, confirmButtonColor: '#d33', cancelButtonText: 'Batal', confirmButtonText: 'Ya, Logout'
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
    }).mount('#tunaiApp');
});
