const { createApp } = Vue

createApp({
    data() {
        return {
            form: {
                email: '',
                username: '',
                password: '',
                confirmPassword: ''
            },
            showPass: false,
            isLoading: false,
            errorMsg: ''
        }
    },
    methods: {
        async handleSignup() {
            this.errorMsg = ''

            if (this.form.password !== this.form.confirmPassword) {
                this.errorMsg = 'Password tidak sama'
                return
            }

            if (this.form.password.length < 6) {
                this.errorMsg = 'Password minimal 6 karakter'
                return
            }

            this.isLoading = true

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nama: this.form.username,
                        email: this.form.email,
                        password: this.form.password,
                        password_confirmation: this.form.confirmPassword
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Simpan token dan user data
                    localStorage.setItem('isLoggedIn', 'true');
                    localStorage.setItem('auth_token', data.data.token);
                    localStorage.setItem('user_data', JSON.stringify(data.data.user));

                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi Berhasil',
                        text: 'Silakan login'
                    }).then(() => {
                        window.location.href = '/auth/login'
                    });
                } else {
                    // Tampilkan error dari server
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        this.errorMsg = errorMessages;
                    } else {
                        this.errorMsg = data.message || 'Registrasi gagal';
                    }
                }
            } catch (error) {
                console.error('Signup error:', error);
                this.errorMsg = 'Terjadi kesalahan koneksi. Coba lagi.';
            } finally {
                this.isLoading = false;
            }
        }
    }
}).mount('#signupApp')
