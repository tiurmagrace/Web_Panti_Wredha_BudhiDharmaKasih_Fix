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
        handleLogin() {
            // reset state
            this.errorMessage = '';
            this.isLoading = true;

            // simulasi request
            setTimeout(() => {
                const savedUser = localStorage.getItem('user_sementara');

                // ❌ user belum daftar
                if (!savedUser) {
                    this.errorMessage = 'Akun tidak ditemukan. Silakan daftar.';
                    this.isLoading = false;
                    return;
                }

                const user = JSON.parse(savedUser);

                // ❌ salah email / password
                if (user.email !== this.email || user.password !== this.password) {
                    this.errorMessage = 'Email atau Password salah!';
                    this.isLoading = false;
                    return;
                }

                // ✅ LOGIN BERHASIL
                localStorage.setItem('isLoggedIn', 'true');

                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil',
                    text: `Selamat datang, ${user.username}`,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // 🔁 redirect pintar
                    const redirect =
                        localStorage.getItem('redirect_after_login') || '/';

                    localStorage.removeItem('redirect_after_login');

                    window.location.href = redirect;
                });

            }, 800);
        }
    }
}).mount('#loginApp');
