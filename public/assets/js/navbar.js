const navbarApp = Vue.createApp({
    data() {
        return {
            isLoggedIn: false,
            currentUser: null
        }
    },
    mounted() {
        const status = localStorage.getItem('isLoggedIn')
        const user = localStorage.getItem('user_sementara')

        if (status === 'true' && user) {
            this.isLoggedIn = true
            this.currentUser = JSON.parse(user)
        }
    },
    methods: {
        confirmLogout() {
            Swal.fire({
                title: 'Keluar?',
                text: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    localStorage.removeItem('isLoggedIn')
                    localStorage.removeItem('user_sementara')
                    localStorage.removeItem('redirect_after_login')
                    window.location.href = '/'
                }
            })
        }
    }
})

navbarApp.mount('#navbarApp')
