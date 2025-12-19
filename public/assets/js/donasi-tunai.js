const { createApp } = Vue;

createApp({
    data() {
        return {
            isLoggedIn: false,
            currentUser: null,

            form: {
                nama: '',
                hp: '',
                email: '',
                catatan: '',
                file: null
            },

            errors: {}
        };
    },

    mounted() {
        // ===============================
        // GERBANG FORM DONASI TUNAI
        // ===============================
        const status = localStorage.getItem('isLoggedIn');
        const userData = localStorage.getItem('user_sementara');

        // ❌ BELUM LOGIN
        if (status !== 'true' || !userData) {
            localStorage.setItem(
                'redirect_after_login',
                '/donatur/donasi-tunai'
            );

            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk mengisi formulir donasi.',
                confirmButtonColor: '#1a5c7a'
            }).then(() => {
                window.location.href = '/auth/login';
            });

            return;
        }

        // ✅ SUDAH LOGIN
        this.isLoggedIn = true;
        this.currentUser = JSON.parse(userData);

        // Autofill
        this.form.nama = this.currentUser.username || '';
        this.form.email = this.currentUser.email || '';
    },

    methods: {
        // ===============================
        // UPLOAD BUKTI TRANSFER
        // ===============================
        handleFileUpload(event) {
            const file = event.target.files[0];

            if (!file) {
                this.form.file = null;
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                this.form.file = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        // ===============================
        // SUBMIT DONASI TUNAI
        // ===============================
        kirimDonasi() {
            this.errors = {};

            if (!this.form.hp) {
                this.errors.hp = 'Nomor HP wajib diisi';
            }

            if (!this.form.file) {
                this.errors.file = 'Bukti transfer wajib diupload';
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }

            // SIMPAN KE LOCALSTORAGE (SIMULASI DB)
            const data = JSON.parse(localStorage.getItem('donasiList')) || [];

            data.push({
                id: Date.now(),
                jenis: 'Tunai',
                nama: this.form.nama,
                email: this.form.email,
                hp: this.form.hp,
                catatan: this.form.catatan,
                foto: this.form.file,
                tanggal: new Date().toLocaleDateString('id-ID'),
                status: 'Menunggu Verifikasi'
            });

            localStorage.setItem('donasiList', JSON.stringify(data));

            // MODAL SUKSES
            const modalEl = document.getElementById('modalSuccess');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // RESET FORM
            this.form.hp = '';
            this.form.catatan = '';
            this.form.file = null;

            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = '';
        },

        // ===============================
        // LOGOUT
        // ===============================
        confirmLogout() {
            Swal.fire({
                title: 'Keluar?',
                text: 'Apakah Anda yakin ingin logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Logout'
            }).then(result => {
                if (result.isConfirmed) {
                    localStorage.removeItem('isLoggedIn');
                    localStorage.removeItem('user_sementara');
                    localStorage.removeItem('redirect_after_login');

                    window.location.href = '/';
                }
            });
        }
    }
}).mount('#tunaiApp');
