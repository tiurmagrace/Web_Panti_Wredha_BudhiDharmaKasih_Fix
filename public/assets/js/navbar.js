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
        const user = localStorage.getItem('user_sementara');

        if (status === 'true' && user) {
            this.isLoggedIn = true;
            this.currentUser = JSON.parse(user);
        }
    },

    methods: {
        confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('isLoggedIn');
                    localStorage.removeItem('currentUser');
                    window.location.href = '/';
                }
            });
        }
    }

}).mount('#navbarApp');
