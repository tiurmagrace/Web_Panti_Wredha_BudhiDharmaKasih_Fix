const { createApp } = Vue;

createApp({
    data() {
        return {
            email: '',
            password: '',
            showPassword: false,
            isLoading: false,
            errorMessage: ''
        };
    },

    methods: {
        async handleLogin() {
            this.errorMessage = '';
            this.isLoading = true;

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: this.email,
                        password: this.password
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Simpan token dan user data
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('auth_token', data.data.token);
                    localStorage.setItem('user_data', JSON.stringify(data.data.user));
                    
                    // Untuk kompatibilitas dengan navbar
                    localStorage.setItem('user_sementara', JSON.stringify({
                        email: data.data.user.email,
                        username: data.data.user.nama
                    }));

                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: `Selamat datang, ${data.data.user.nama}`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        const redirect = localStorage.getItem('redirect_after_login') || '/';
                        localStorage.removeItem('redirect_after_login');
                        window.location.href = redirect;
                    });
                } else {
                    this.errorMessage = data.message || 'Email atau Password salah!';
                }
            } catch (error) {
                console.error('Login error:', error);
                this.errorMessage = 'Terjadi kesalahan koneksi. Coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    }
}).mount('#loginApp');
