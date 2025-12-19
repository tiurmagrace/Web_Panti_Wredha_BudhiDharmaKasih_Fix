const { createApp } = Vue;

createApp({
    data() {
        return {
            isLoggedIn: false,
            currentUser: null,
            searchQuery: ''
        };
    },

    mounted() {
        this.checkAuth();
    },

    methods: {
        // ===============================
        // 🔒 AUTH CHECK (WAJIB)
        // ===============================
        checkAuth() {
            const status = localStorage.getItem('isLoggedIn');
            const userData = localStorage.getItem('user_sementara');

            // ❌ BELUM LOGIN
            if (status !== 'true' || !userData) {
                // simpan tujuan
                localStorage.setItem(
                    'redirect_after_login',
                    '/donatur/notifikasi'
                );

                Swal.fire({
                    icon: 'info',
                    title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu untuk melihat notifikasi.',
                    confirmButtonColor: '#1a5c7a'
                }).then(() => {
                    window.location.href = '/auth/login';
                });

                return;
            }

            // ✅ SUDAH LOGIN
            this.isLoggedIn = true;
            this.currentUser = JSON.parse(userData);
        },

        // ===============================
        // 🔍 SEARCH DI HALAMAN
        // ===============================
        performSearch() {
            if (!this.searchQuery) return;

            // bersihkan highlight lama
            document.querySelectorAll('.highlight-text')
                .forEach(el => el.outerHTML = el.innerText);

            const term = this.searchQuery.trim();
            if (term.length < 3) {
                Swal.fire('Info', 'Kata kunci minimal 3 huruf', 'info');
                return;
            }

            const content = document.querySelector('main');
            if (!content) return;

            const regex = new RegExp(`(${term})`, 'gi');
            let found = false;

            function highlight(node) {
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
                    node.childNodes.forEach(child => highlight(child));
                }
            }

            highlight(content);

            if (found) {
                document.querySelector('.highlight-text')
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
}).mount('#notifikasiApp');
