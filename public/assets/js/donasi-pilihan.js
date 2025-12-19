const { createApp } = Vue;

createApp({
    data() {
        return {
            isLoggedIn: false,
            currentUser: null,
            logoutModalInstance: null,
            searchQuery: ''
        };
    },

    mounted() {
        // ✅ HALAMAN INI PUBLIC → CUMA CEK STATUS
        this.cekStatusLogin();
    },

    methods: {
        // =========================
        // CEK STATUS LOGIN
        // =========================
        cekStatusLogin() {
            const status = localStorage.getItem('isLoggedIn');
            const userData = localStorage.getItem('user_sementara');

            if (status === 'true' && userData) {
                this.isLoggedIn = true;
                this.currentUser = JSON.parse(userData);
            } else {
                this.isLoggedIn = false;
                this.currentUser = null;
            }
        },

        // =========================
        // LOGOUT
        // =========================
        showLogoutModal() {
            const modalEl = document.getElementById('logoutModal');
            if (!modalEl) return;

            this.logoutModalInstance = new bootstrap.Modal(modalEl);
            this.logoutModalInstance.show();
        },

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
        },

        // =========================
        // GERBANG DONASI (INI YANG PENTING)
        // =========================
        pilihDonasi(jenis) {
            const tujuanRoute =
                jenis === 'barang'
                    ? '/donatur/donasi-barang'
                    : '/donatur/donasi-tunai';

            // ✅ SUDAH LOGIN → LANGSUNG MASUK FORM
            if (this.isLoggedIn) {
                window.location.href = tujuanRoute;
                return;
            }

            // ❌ BELUM LOGIN → SIMPAN TUJUAN TERAKHIR
            localStorage.setItem('redirect_after_login', tujuanRoute);

            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Untuk mengisi formulir donasi, silakan Login atau Daftar akun terlebih dahulu.',
                confirmButtonColor: '#1a5c7a'
            }).then(() => {
                window.location.href = '/auth/login';
            });
        },

        // =========================
        // SEARCH (TIDAK DISENTUH)
        // =========================
        performSearch() {
            if (!this.searchQuery) return;

            document
                .querySelectorAll('.highlight-text')
                .forEach(el => { el.outerHTML = el.innerText; });

            const term = this.searchQuery.trim();
            if (term.length < 3) {
                Swal.fire('Info', 'Kata kunci minimal 3 huruf', 'info');
                return;
            }

            const content = document.querySelector('main');
            const regex = new RegExp(`(${term})`, 'gi');
            let found = false;

            function highlightText(node) {
                if (node.nodeType === 3 && regex.test(node.data)) {
                    const span = document.createElement('span');
                    span.innerHTML = node.data.replace(
                        regex,
                        '<span class="highlight-text">$1</span>'
                    );
                    node.parentNode.replaceChild(span, node);
                    found = true;
                } else if (
                    node.nodeType === 1 &&
                    node.childNodes &&
                    !/(script|style)/i.test(node.tagName)
                ) {
                    node.childNodes.forEach(child => highlightText(child));
                }
            }

            highlightText(content);

            if (found) {
                document
                    .querySelector('.highlight-text')
                    ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                Swal.fire(
                    'Tidak Ditemukan',
                    `Kata "${term}" tidak ada di halaman ini.`,
                    'warning'
                );
            }
        }
    }
}).mount('#donasiPilihanApp');
