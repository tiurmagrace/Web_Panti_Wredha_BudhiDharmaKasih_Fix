const { createApp } = Vue;

createApp({
    data() {
        return {
            step: 1, // 1: email, 2: kode, 3: password baru
            email: '',
            code: '',
            password: '',
            passwordConfirm: '',
            showPass1: false,
            showPass2: false,
            isLoading: false
        }
    },
    methods: {
        // Step 1: Kirim kode verifikasi ke email
        async kirimKode() {
            if (!this.email) {
                Swal.fire('Error', 'Masukkan email Anda', 'error');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/api/auth/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: this.email })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kode Terkirim!',
                        text: 'Silakan cek email Anda untuk kode verifikasi',
                        confirmButtonColor: '#1a5c7a'
                    });
                    this.step = 2;
                } else {
                    Swal.fire('Error', data.message || 'Email tidak terdaftar', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        // Step 2: Verifikasi kode
        async verifikasiKode() {
            if (!this.code || this.code.length < 4) {
                Swal.fire('Error', 'Masukkan kode verifikasi yang valid', 'error');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/api/auth/verify-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        code: this.code
                    })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kode Valid!',
                        text: 'Silakan buat password baru',
                        confirmButtonColor: '#1a5c7a',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    this.step = 3;
                } else {
                    Swal.fire('Kode Salah!', data.message || 'Kode verifikasi tidak valid', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        // Step 3: Reset password
        async resetPassword() {
            if (this.password !== this.passwordConfirm) {
                Swal.fire('Error', 'Password konfirmasi tidak sama!', 'error');
                return;
            }

            if (this.password.length < 6) {
                Swal.fire('Error', 'Password minimal 6 karakter', 'error');
                return;
            }

            this.isLoading = true;

            try {
                const response = await fetch('/api/auth/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        code: this.code,
                        password: this.password,
                        password_confirmation: this.passwordConfirm
                    })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Password berhasil diubah. Silakan login.',
                        confirmButtonColor: '#1a5c7a'
                    }).then(() => {
                        window.location.href = '/admin/login';
                    });
                } else {
                    Swal.fire('Error', data.message || 'Gagal mereset password', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    }
}).mount('#lupaApp');
