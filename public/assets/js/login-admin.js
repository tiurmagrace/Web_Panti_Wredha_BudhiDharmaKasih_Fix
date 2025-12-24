const { createApp } = Vue;

createApp({
    data() {
        return {
            username: '', // ini sebenarnya email
            password: '',
            showPassword: false,
            isLoading: false
        }
    },
    methods: {
        async handleLogin() {
            this.isLoading = true;

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.username,
                        password: this.password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Cek apakah user adalah admin
                    if (data.data.user.role !== 'admin') {
                        Swal.fire('Akses Ditolak', 'Anda bukan admin!', 'error');
                        this.isLoading = false;
                        return;
                    }

                    // Simpan token dan data user
                    localStorage.setItem('adminLoggedIn', 'true');
                    localStorage.setItem('admin_token', data.data.token);
                    localStorage.setItem('admin_user', JSON.stringify(data.data.user));

                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: `Selamat Datang, ${data.data.user.nama}!`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/admin';
                    });
                } else {
                    Swal.fire('Login Gagal', data.message || 'Email atau Password salah!', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                Swal.fire('Error', 'Terjadi kesalahan koneksi. Coba lagi.', 'error');
            } finally {
                this.isLoading = false;
            }
        }
    }
}).mount('#loginApp');
