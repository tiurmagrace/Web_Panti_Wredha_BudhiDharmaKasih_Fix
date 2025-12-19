const { createApp } = Vue;

createApp({
    data() { 
        return { 
            pass1: '',
            pass2: '',
            showPass1: false,
            showPass2: false,
            isLoading: false,
            errorMsg: '' 
        } 
    },
    methods: {
        resetPassword() {
            if (this.pass1 !== this.pass2) {
                this.errorMsg = "Password tidak sama!";
                return;
            }

            this.isLoading = true;

            setTimeout(() => {
                // Update password di database (simulasi)
                let storedUser = localStorage.getItem('user_sementara');
                if (storedUser) {
                    let user = JSON.parse(storedUser);
                    user.password = this.pass1;
                    localStorage.setItem('user_sementara', JSON.stringify(user));
                }

                // ✅ REDIRECT KE ROUTE LARAVEL
                window.location.href = '/auth/reset-sukses';

            }, 1500);
        }
    }
}).mount('#resetApp');
