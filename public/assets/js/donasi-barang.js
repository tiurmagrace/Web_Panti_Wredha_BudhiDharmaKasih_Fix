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
                jumlah: '',     // ✅ TAMBAHAN: Jumlah barang (misal: 5 Karung)
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
        // SUBMIT DONASI BARANG (✅ FIXED)
        // ===============================
        kirimDonasi() {
            this.errors = {};

            if (!this.form.hp) {
                this.errors.hp = 'Nomor HP wajib diisi';
            }

            if (!this.form.barang) {
                this.errors.barang = 'Nama barang wajib diisi';
            }

            if (!this.form.jumlah) {
                this.errors.jumlah = 'Jumlah barang wajib diisi';
            }

            if (!this.form.fileBukti) {
                this.errors.fileBukti = 'Foto barang / resi wajib diupload';
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
                jenis: 'Barang',                // ✅ Jenis donasi
                detail: this.form.barang,       // ✅ Detail bantuan (nama barang)
                jumlah: this.form.jumlah,       // ✅ Jumlah barang
                status: 'Tidak Langsung',       // ✅ Status (dari donatur online)
                petugas: '-',                   // ✅ Petugas (belum ada, nanti admin isi)
                kategori: this.form.kategori,   // Data tambahan untuk stok barang
                email: this.form.email,         // Data tambahan untuk laporan
                hp: this.form.hp,               // Data tambahan
                catatan: this.form.catatan,     // Data tambahan
                foto: this.form.fileBukti       // Foto barang/resi
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
                text: `Donasi Barang masuk dari: ${this.form.nama} (${this.form.barang} - ${this.form.jumlah})`, 
                time: jam 
            });
            localStorage.setItem('activityLog', JSON.stringify(logs));

            console.log('✅ Donasi Barang berhasil disimpan!');

            // TAMPILKAN MODAL SUKSES
            const modalEl = document.getElementById('modalSuccess');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }

            // RESET FORM (BIAR GA DOUBLE SUBMIT)
            this.form.hp = '';
            this.form.barang = '';
            this.form.jumlah = '';
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