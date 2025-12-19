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
                kategori: '',
                barang: '',
                catatan: '',
                fileBukti: null
            },

            errors: {}
        };
    },

    mounted() {
        // ===============================
        // GERBANG FORM DONASI BARANG
        // ===============================
        const status = localStorage.getItem('isLoggedIn');
        const userData = localStorage.getItem('user_sementara');

        // ❌ BELUM LOGIN → SIMPAN TUJUAN + REDIRECT
        if (status !== 'true' || !userData) {
            localStorage.setItem(
                'redirect_after_login',
                '/donatur/donasi-barang'
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
        // UPLOAD FILE
        // ===============================
        handleFileUpload(event) {
            const file = event.target.files[0];

            if (!file) {
                this.form.fileBukti = null;
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                this.form.fileBukti = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        // ===============================
        // SUBMIT DONASI BARANG
        // ===============================
        kirimDonasi() {
            this.errors = {};

            if (!this.form.hp) {
                this.errors.hp = 'Nomor HP wajib diisi';
            }

            if (!this.form.barang) {
                this.errors.barang = 'Nama barang wajib diisi';
            }

            if (!this.form.fileBukti) {
                this.errors.fileBukti = 'Foto barang / resi wajib diupload';
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }

            // SIMPAN KE LOCALSTORAGE (SIMULASI DB)
            const data = JSON.parse(localStorage.getItem('donasiList')) || [];

            data.push({
                id: Date.now(),
                jenis: 'Barang',
                nama: this.form.nama,
                email: this.form.email,
                hp: this.form.hp,
                kategori: this.form.kategori,
                barang: this.form.barang,
                catatan: this.form.catatan,
                foto: this.form.fileBukti,
                tanggal: new Date().toLocaleDateString('id-ID'),
                status: 'Menunggu Verifikasi'
            });

            localStorage.setItem('donasiList', JSON.stringify(data));

            // TAMPILKAN MODAL SUKSES
            const modalEl = document.getElementById('modalSuccess');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // RESET FORM (BIAR GA DOUBLE SUBMIT)
            this.form.hp = '';
            this.form.barang = '';
            this.form.catatan = '';
            this.form.fileBukti = null;

            // reset input file HTML
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
}).mount('#donasiApp');
