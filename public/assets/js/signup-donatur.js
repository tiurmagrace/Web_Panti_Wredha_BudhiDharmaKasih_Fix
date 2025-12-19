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
        handleSignup() {
            this.errorMsg = ''

            if (this.form.password !== this.form.confirmPassword) {
                this.errorMsg = 'Password tidak sama'
                return
            }

            this.isLoading = true

            setTimeout(() => {
                const user = {
                    email: this.form.email,
                    username: this.form.username,
                    password: this.form.password
                }

                // ✅ SIMPAN KE KEY YANG BENAR
                localStorage.setItem('user_sementara', JSON.stringify(user))

                this.isLoading = false

                Swal.fire({
                    icon: 'success',
                    title: 'Registrasi Berhasil',
                    text: 'Silakan login'
                }).then(() => {
                    window.location.href = '/auth/login'
                })
            }, 1000)
        }
    }
}).mount('#signupApp')
