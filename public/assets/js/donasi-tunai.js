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
                jumlah: '',  // ✅ TAMBAHAN: Jumlah uang
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
        // SUBMIT DONASI TUNAI (✅ FIXED)
        // ===============================
        kirimDonasi() {
            this.errors = {};

            if (!this.form.hp) {
                this.errors.hp = 'Nomor HP wajib diisi';
            }

            if (!this.form.jumlah) {
                this.errors.jumlah = 'Jumlah donasi wajib diisi';
            }

            if (!this.form.file) {
                this.errors.file = 'Bukti transfer wajib diupload';
            }

            if (Object.keys(this.errors).length > 0) {
                return;
            }

            // ✅ FIXED: Format data sesuai dengan kelola-donasi admin
            const dataLama = JSON.parse(localStorage.getItem('donasiList')) || [];

            // Format tanggal
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();
            const tanggalFormatted = `${day}/${month}/${year}`;
            const tanggalRaw = `${year}-${month}-${day}`;

            const donasiBaru = {
                id: Date.now(),
                tanggal: tanggalFormatted,      // Format: dd/mm/yyyy
                tanggal_raw: tanggalRaw,        // Format: yyyy-mm-dd
                donatur: this.form.nama,        // ✅ Pakai 'donatur' bukan 'nama'
                jenis: 'Tunai',                 // ✅ Jenis donasi
                detail: 'Uang Tunai',           // ✅ Detail bantuan
                jumlah: this.form.jumlah,       // ✅ Jumlah uang
                status: 'Tidak Langsung',       // ✅ Status (dari donatur online)
                petugas: '-',                   // ✅ Petugas (belum ada, nanti admin isi)
                email: this.form.email,         // Data tambahan untuk laporan
                hp: this.form.hp,               // Data tambahan
                catatan: this.form.catatan,     // Data tambahan
                foto: this.form.file            // Bukti transfer
            };

            dataLama.push(donasiBaru);
            localStorage.setItem('donasiList', JSON.stringify(dataLama));

            // ✅ SIMPAN LOG AKTIVITAS
            let logs = JSON.parse(localStorage.getItem('activityLog')) || [];
            let jam = new Date().toLocaleString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            logs.push({ 
                text: `Donasi Tunai masuk dari: ${this.form.nama} (Rp ${this.form.jumlah})`, 
                time: jam 
            });
            localStorage.setItem('activityLog', JSON.stringify(logs));

            console.log('✅ Donasi Tunai berhasil disimpan!');

            // MODAL SUKSES
            const modalEl = document.getElementById('modalSuccess');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // RESET FORM
            this.form.hp = '';
            this.form.jumlah = '';
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