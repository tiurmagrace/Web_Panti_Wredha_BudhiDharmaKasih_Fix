console.log('NAVBAR VUE LOADED');

Vue.createApp({
    data() {
        return {
            isLoggedIn: false,
            currentUser: null
        }
    },

    mounted() {
        const status = localStorage.getItem('isLoggedIn');
        const userData = localStorage.getItem('user_data');

        if (status === 'true' && userData) {
            this.isLoggedIn = true;
            this.currentUser = JSON.parse(userData);
        } else {
            // Fallback ke user_sementara untuk kompatibilitas
            const user = localStorage.getItem('user_sementara');
            if (status === 'true' && user) {
                this.isLoggedIn = true;
                this.currentUser = JSON.parse(user);
            }
        }
    },

    methods: {
        async confirmLogout() {
            const result = await Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const token = localStorage.getItem('auth_token');
                    
                    if (token) {
                        // Panggil API logout
                        await fetch('/api/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + token
                            }
                        });
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                }

                // Clear semua data localStorage
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_data');
                localStorage.removeItem('user_sementara');
                localStorage.removeItem('currentUser');
                
                window.location.href = '/';
            }
        }
    }

}).mount('#navbarApp');
